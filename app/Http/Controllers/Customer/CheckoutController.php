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
        // 1) Validate ฟอร์ม + สลิป
        $data = $request->validate([
            'shipping_name'    => ['required','string','max:255'],
            // เบอร์ไทย 10 หลัก เริ่มด้วย 0
            'shipping_phone'   => ['required','regex:/^0\d{9}$/'],
            'shipping_address' => ['required','string','max:2000'],
            'payment_slip'     => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
            'confirm'          => ['required','accepted'],
        ], [
            'shipping_phone.regex' => 'กรุณากรอกเบอร์โทรให้ถูกต้อง (10 หลัก เริ่มด้วย 0)',
            'confirm.accepted'     => 'กรุณาติ๊กยืนยันการสั่งซื้อ',
        ]);

        $userId = auth()->id();

        // 2) โหลดตะกร้าจาก DB
        $items = DB::table('carts')
            ->join('cart_items', 'cart_items.cart_id', '=', 'carts.cart_id')
            ->join('products', 'products.product_id', '=', 'cart_items.product_id')
            ->where('carts.user_id', $userId)
            ->select([
                'cart_items.product_id',
                'cart_items.quantity',
                'cart_items.price',              // snapshot ราคา
                'products.name as product_name',
                'products.seller_id',
                'products.stock_quantity',
            ])
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'ตะกร้าว่าง');
        }

        // 3) สินค้าทุกชิ้นต้องมี seller_id
        foreach ($items as $row) {
            if (empty($row->seller_id)) {
                return back()->with('error', "สินค้า \"{$row->product_name}\" ยังไม่ผูกกับผู้ขาย (seller_id)");
            }
        }

        // 4) อัปโหลดสลิป 1 ไฟล์ (ถ้ามี) แล้วใช้ path เดียวกับทุกออเดอร์
        $slipPath = null;
        if ($request->hasFile('payment_slip')) {
            $slipPath = $request->file('payment_slip')->store('payment_slips', 'public');
        }
        $hasSlip = (bool) $slipPath;

        // 5) แยกสินค้าตามผู้ขาย
        $bySeller = $items->groupBy('seller_id');

        // 6) ทำทุกอย่างในทรานแซกชัน
        DB::transaction(function () use ($bySeller, $data, $userId, $slipPath, $hasSlip) {

            foreach ($bySeller as $sellerId => $group) {

                // 6.1) ล็อกสต็อกกันแข่งกันตัด
                $productIds   = $group->pluck('product_id')->all();
                $lockProducts = \App\Models\Product::whereIn('product_id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('product_id');

                // 6.2) ตรวจสต็อกพอไหม
                foreach ($group as $row) {
                    $p = $lockProducts[$row->product_id] ?? null;
                    if (!$p) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'cart' => "ไม่พบสินค้า (ID: {$row->product_id})",
                        ]);
                    }
                    if ((int)$p->stock_quantity < (int)$row->quantity) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'cart' => "สินค้า {$row->product_name} สต็อกไม่เพียงพอ (คงเหลือ {$p->stock_quantity})",
                        ]);
                    }
                }

                // 6.3) ยอดรวมของร้านนี้
                $total = $group->sum(fn($r) => (float)$r->price * (int)$r->quantity);

                // 6.4) สร้างคำสั่งซื้อ (สลิปเดียว แชร์ทุกออเดอร์)
                $order = \App\Models\Order::create([
                    'user_id'          => (int)$userId,
                    'seller_id'        => (int)$sellerId,
                    'order_date'       => now('Asia/Bangkok'),
                    'status'           => 'pending',
                    'payment_status'   => $hasSlip ? 'pending' : 'unpaid',
                    'payment_slip'     => $slipPath,      // อาจเป็น null ถ้าไม่ได้แนบ
                    'total_amount'     => (float)$total,
                    'shipping_name'    => $data['shipping_name'],
                    'shipping_phone'   => $data['shipping_phone'],
                    'shipping_address' => $data['shipping_address'],
                ]);

                // 6.5) ไอเท็ม + ตัดสต็อกแบบกันติดลบ
                foreach ($group as $row) {
                    \App\Models\OrderItem::create([
                        'order_id'   => (int)$order->order_id,
                        'product_id' => (int)$row->product_id,
                        'name'       => $row->product_name,
                        'quantity'   => (int)$row->quantity,
                        'price'      => (float)$row->price,
                        // ถ้ามีคอลัมน์ seller_id ใน order_items ให้เก็บด้วย
                        'seller_id'  => (int)$sellerId,
                    ]);

                    // decrement แบบกันติดลบ
                    $affected = \App\Models\Product::where('product_id', (int)$row->product_id)
                        ->where('stock_quantity', '>=', (int)$row->quantity)
                        ->decrement('stock_quantity', (int)$row->quantity);

                    if ($affected === 0) {
                        // กันกรณีแข่งกันตัดจนไม่พอในจังหวะนี้
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'cart' => "ตัดสต็อกไม่สำเร็จสำหรับสินค้า ID {$row->product_id}",
                        ]);
                    }
                }
            }

            // 6.6) ล้างตะกร้าหลังสั่งซื้อสำเร็จ
            DB::table('cart_items')
                ->whereIn('cart_id', function ($q) use ($userId) {
                    $q->select('cart_id')->from('carts')->where('user_id', $userId);
                })
                ->delete();
        });

        return redirect()
            ->route('customer.orders.index')
            ->with('status', $hasSlip
                ? 'สั่งซื้อสำเร็จและส่งสลิปเรียบร้อยแล้ว • รอแอดมินตรวจสอบ'
                : 'สั่งซื้อสำเร็จ • คุณสามารถอัปโหลดสลิปชำระเงินภายหลังได้');
    }
}
