<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q',''));

        $orders = Order::query()
            ->where('seller_id', auth()->id()) // ให้ seller เห็นเฉพาะของตัวเอง
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('shipping_name', 'like', "%{$q}%")
                      ->orWhere('shipping_phone', 'like', "%{$q}%")
                      ->orWhere('shipping_address', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('order_id')
            ->paginate(10)
            ->withQueryString();

        $statusMap = [
            'pending'   => 'รอดำเนินการ',
            'paid'      => 'ชำระแล้ว',
            'shipped'   => 'จัดส่งแล้ว',
            'completed' => 'เสร็จสิ้น',
            'canceled'  => 'ยกเลิก',
        ];

        return view('seller.orders.index', compact('orders','q','statusMap'));
    }

    // public function index(Request $request)
    // {
    //     $q = trim($request->get('q',''));
    //     $sellerId = auth()->id();

    //     $pk = (new Order)->getKeyName();

    //     // เลือกเฉพาะ order ที่มี item ใดๆ เป็นสินค้าของ seller นี้
    //     $orders = Order::query()
    //         ->whereHas('items.product', function($q2) use ($sellerId) {
    //             $q2->where('seller_id', $sellerId);
    //         })
    //         ->when($q !== '', function($s) use ($q) {
    //             $s->where(function($w) use ($q) {
    //                 $w->where('shipping_name','like',"%{$q}%")
    //                   ->orWhere('shipping_phone','like',"%{$q}%")
    //                   ->orWhere('shipping_address','like',"%{$q}%");
    //             });
    //         })
    //         ->with(['items' => function($q3) use ($sellerId) {
    //             // โหลดเฉพาะรายการของ seller นี้ (เวลาไปแสดงใน modal รายการสินค้า)
    //             $q3->whereHas('product', fn($qq) => $qq->where('seller_id', $sellerId))
    //                ->with('product');
    //         }])
    //         ->orderByDesc($pk)
    //         ->paginate(10)
    //         ->withQueryString();

    //     // สำหรับ dropdown สถานะ (ในกรณีคุณมีใน view)
    //     $statusMap = [
    //         'pending'   => 'รอดำเนินการ',
    //         'paid'      => 'ชำระแล้ว',
    //         'shipped'   => 'จัดส่งแล้ว',
    //         'completed' => 'เสร็จสิ้น',
    //         'canceled'  => 'ยกเลิก',
    //     ];

    //     return view('seller.orders.index', compact('orders','q','statusMap'));
    // }

    public function updateStatus(Request $request, Order $order)
    {
        // กำหนดสถานะที่อนุญาต
        $allowed = ['pending','paid','shipped','completed','canceled'];

        $data = $request->validate([
            'status' => ['required','in:'.implode(',', $allowed)],
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('status', 'อัปเดตสถานะคำสั่งซื้อ #'.$order->order_id.' เรียบร้อย');
    }
}
