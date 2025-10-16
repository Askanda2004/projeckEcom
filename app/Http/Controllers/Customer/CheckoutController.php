<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    /**
     * แสดงหน้า Checkout (โหลดตะกร้าจาก DB)
     */
    public function create()
    {
        $userId = auth()->id();

        // ดึงตะกร้าพร้อมสินค้า
        $items = DB::table('carts')
            ->join('cart_items', 'cart_items.cart_id', '=', 'carts.cart_id')
            ->join('products', 'products.product_id', '=', 'cart_items.product_id')
            ->where('carts.user_id', $userId)
            ->select([
                'cart_items.product_id',
                'cart_items.quantity',
                'cart_items.price',
                'cart_items.size',
                'cart_items.color',
                'cart_items.image_url',
                'products.name as product_name',
                'products.seller_id',
            ])
            ->get();

        $cart = $items->map(function ($row) {
            return [
                'product_id' => (int) $row->product_id,
                'name'       => $row->product_name,
                'price'      => (float) $row->price,
                'qty'        => (int) $row->quantity,
                'size'       => $row->size,
                'color'      => $row->color,
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
     * สั่งซื้อสินค้า (สร้าง order + order_items)
     */
    public function store(Request $request)
    {
        // ✅ ตรวจฟอร์ม + ไฟล์สลิป
        $data = $request->validate([
            'shipping_name'    => ['required','string','max:255'],
            'shipping_phone'   => ['required','string','max:30'],
            'shipping_address' => ['required','string','max:2000'],
            'payment_slip'     => ['nullable','image','max:2048'], // สลิปโอนเงิน
            'confirm'          => ['required','accepted'],
        ], [
            'confirm.accepted' => 'กรุณาติ๊กยืนยันการสั่งซื้อ',
        ]);

        $userId = auth()->id();

        // ดึงข้อมูลตะกร้า
        $items = DB::table('carts')
            ->join('cart_items', 'cart_items.cart_id', '=', 'carts.cart_id')
            ->join('products', 'products.product_id', '=', 'cart_items.product_id')
            ->where('carts.user_id', $userId)
            ->select([
                'cart_items.product_id',
                'cart_items.quantity',
                'cart_items.price',
                'products.name as product_name',
                'products.seller_id',
                'products.stock_quantity',
            ])
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'ตะกร้าว่าง');
        }

        // ตรวจ seller_id ของสินค้าทั้งหมด
        foreach ($items as $row) {
            if (!$row->seller_id) {
                return back()->with('error', "สินค้า \"{$row->product_name}\" ยังไม่ผูกกับผู้ขาย (seller_id)");
            }
        }

        // กลุ่มสินค้าแยกตาม seller_id
        $bySeller = $items->groupBy('seller_id');

        // ✅ ถ้ามีอัปโหลดสลิป เก็บลง storage/public/payment_slips
        $slipPath = null;
        if ($request->hasFile('payment_slip')) {
            $slipPath = $request->file('payment_slip')->store('payment_slips', 'public');
        }

        DB::transaction(function () use ($bySeller, $data, $userId, $slipPath) {
            foreach ($bySeller as $sellerId => $group) {
                // ล็อกสินค้าไว้กัน race condition
                $productIds = $group->pluck('product_id')->all();
                $lockProducts = Product::whereIn('product_id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('product_id');

                // ตรวจสต็อก
                foreach ($group as $row) {
                    $p = $lockProducts[$row->product_id] ?? null;
                    if (!$p || $p->stock_quantity < $row->quantity) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'cart' => "สินค้า {$row->product_name} สต็อกไม่เพียงพอ",
                        ]);
                    }
                }

                // รวมยอดแต่ละ seller
                $total = $group->sum(fn($r) => $r->price * $r->quantity);

                // ✅ สร้าง order
                $order = Order::create([
                    'user_id'          => $userId,
                    'seller_id'        => $sellerId,
                    'order_date'       => now('Asia/Bangkok'),
                    'status'           => 'pending',           // ยังไม่ตรวจสอบ
                    'payment_status'   => 'pending',           // รอการตรวจสอบ
                    'payment_slip'     => $slipPath,           // เก็บ path สลิป
                    'total_amount'     => $total,
                    'shipping_name'    => $data['shipping_name'],
                    'shipping_phone'   => $data['shipping_phone'],
                    'shipping_address' => $data['shipping_address'],
                ]);

                // ✅ สร้าง order_items
                foreach ($group as $row) {
                    OrderItem::create([
                        'order_id'   => $order->order_id,
                        'product_id' => $row->product_id,
                        'name'       => $row->product_name,
                        'quantity'   => $row->quantity,
                        'price'      => $row->price,
                        'seller_id'  => $sellerId,
                    ]);

                    // ตัดสต็อก
                    Product::where('product_id', $row->product_id)
                        ->decrement('stock_quantity', (int)$row->quantity);
                }
            }

            // ✅ ล้างตะกร้าเมื่อสั่งซื้อเสร็จ
            DB::table('cart_items')
                ->whereIn('cart_id', function ($q) use ($userId) {
                    $q->select('cart_id')->from('carts')->where('user_id', $userId);
                })
                ->delete();
        });

        return redirect()
            ->route('customer.orders.index')
            ->with('status', 'สั่งซื้อสำเร็จ! โปรดรอเจ้าหน้าที่ตรวจสอบการชำระเงิน');
    }
}
