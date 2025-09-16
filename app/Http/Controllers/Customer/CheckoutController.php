<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    /**
     * สรุปรายการจาก DB cart_items + products แล้วส่งให้หน้า checkout
     */
    public function create()
    {
        $userId = auth()->id();

        // ดึงตะกร้าของผู้ใช้จาก DB (join products เพื่อได้ข้อมูลครบ)
        $items = DB::table('carts')
            ->join('cart_items', 'cart_items.cart_id', '=', 'carts.cart_id')
            ->join('products', 'products.product_id', '=', 'cart_items.product_id')
            ->where('carts.user_id', $userId)
            ->select([
                'cart_items.product_id',
                'cart_items.quantity',
                'cart_items.price',          // snapshot ตอนใส่ตะกร้า
                'cart_items.size',
                'cart_items.color',
                'cart_items.image_url',
                'products.name as product_name',
                'products.seller_id',
            ])
            ->get();

        // map ให้ view ใช้เหมือนเดิม (ตัวแปร $cart)
        $cart = $items->map(function ($row) {
            return [
                'product_id' => (int) $row->product_id,
                'name'       => $row->product_name,
                'price'      => (float) $row->price,
                'qty'        => (int) $row->quantity,
                'size'       => $row->size,
                'color'      => $row->color,
                // แสดงรูปจาก storage ถ้ามี
                'image'      => $row->image_url ? asset('storage/'.$row->image_url) : null,
                'seller_id'  => (int) $row->seller_id,
            ];
        });

        $total = $cart->sum(fn($r) => $r['price'] * $r['qty']);

        return view('customer.checkout', [
            'cart'  => $cart,
            'total' => $total,
        ]);
    }

    /**
     * วางคำสั่งซื้อ:
     * - ตรวจฟอร์มที่อยู่จัดส่ง
     * - โหลดตะกร้าจาก DB
     * - แยกตาม seller_id
     * - สร้าง orders + order_items
     * - ตัดสต็อก (transaction-safe)
     * - ล้าง cart_items ของผู้ใช้
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'shipping_name'    => ['required','string','max:255'],
            'shipping_phone'   => ['required','string','max:30'],
            'shipping_address' => ['required','string','max:2000'],
            'confirm'          => ['required','accepted'],
            'payment_slip'     => ['required','image','mimes:jpg,jpeg,png','max:5120'], // 5MB
            ], 
        
            [
            'confirm.accepted' => 'กรุณาติ๊กยืนยันการสั่งซื้อ',
        ]);
        
        // $path = $request->file('payment_slip')->store('payment_slips', 'public');

        $userId = auth()->id();

        // โหลด cart จาก DB
        $items = DB::table('carts')
            ->join('cart_items', 'cart_items.cart_id', '=', 'carts.cart_id')
            ->join('products', 'products.product_id', '=', 'cart_items.product_id')
            ->where('carts.user_id', $userId)
            ->select([
                'cart_items.product_id',
                'cart_items.quantity',
                'cart_items.price',      // snapshot ตอน add to cart
                'products.name as product_name',
                'products.seller_id',
                'products.stock_quantity',
            ])
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'ตะกร้าว่าง');
        }

        // ตรวจว่า product ทุกตัวมี seller_id
        foreach ($items as $row) {
            if (!$row->seller_id) {
                return back()->with('error', "สินค้า \"{$row->product_name}\" ยังไม่ถูกผูกกับผู้ขาย (seller_id)");
            }
        }

        // จัดกลุ่มตาม seller_id
        $bySeller = $items->groupBy('seller_id');

        DB::transaction(function () use ($bySeller, $data, $userId) {
            foreach ($bySeller as $sellerId => $group) {

                // lock สินค้าที่จะตัดสต็อก
                $productIds = $group->pluck('product_id')->all();
                $lockProducts = Product::whereIn('product_id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('product_id');

                // ตรวจสต็อก
                foreach ($group as $row) {
                    $p = $lockProducts[$row->product_id] ?? null;
                    if (!$p) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'cart' => "ไม่พบสินค้า (ID: {$row->product_id})",
                        ]);
                    }
                    if ((int)$p->stock_quantity < (int)$row->quantity) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'cart' => "สต็อกสินค้า \"{$p->name}\" ไม่พอ (คงเหลือ {$p->stock_quantity}, ต้องการ {$row->quantity})",
                        ]);
                    }
                }

                // ยอดรวมของผู้ขายแต่ละคน
                $total = $group->sum(fn($r) => (float)$r->price * (int)$r->quantity);

                // สร้าง order 1 ใบสำหรับ seller รายนี้
                $order = Order::create([
                    'user_id'          => auth()->id(),
                    'seller_id'        => $sellerId,  
                    'order_date'       => now(),
                    'status'           => 'pending',
                    'total_amount'     => $total,
                    'shipping_name'    => $data['shipping_name'],
                    'shipping_phone'   => $data['shipping_phone'],
                    'shipping_address' => $data['shipping_address'],
                ]);

                // ใส่รายการ + ตัดสต็อก
                foreach ($group as $row) {
                    OrderItem::create([
                        'order_id'   => $order->order_id,
                        'product_id' => (int) $row->product_id,
                        'quantity'   => (int) $row->quantity,
                        'price'      => (float) $row->price,
                    ]);

                    // ตัดสต็อกแบบกันติดลบ
                    $affected = Product::where('product_id', (int)$row->product_id)
                        ->where('stock_quantity', '>=', (int)$row->quantity)
                        ->decrement('stock_quantity', (int)$row->quantity);

                    if ($affected === 0) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'cart' => "ตัดสต็อกไม่สำเร็จ: ID {$row->product_id}",
                        ]);
                    }
                }
            }

            // ล้าง cart_items ของผู้ใช้เมื่อสั่งซื้อทุกใบสำเร็จ
            DB::table('cart_items')
              ->whereIn('cart_id', function ($q) use ($userId) {
                  $q->select('cart_id')->from('carts')->where('user_id', $userId);
              })->delete();
        });

        return redirect()->route('customer.shop')
            ->with('status', 'สั่งซื้อสำเร็จแล้ว! ระบบสร้างออเดอร์แยกตามผู้ขายให้เรียบร้อย');
    }
}
