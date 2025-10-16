<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /** แสดงรายการคำสั่งซื้อทั้งหมดที่มีสลิป */
    public function index(Request $request)
    {
        $q = $request->input('q');

        $orders = Order::query()
            ->when($q, function($query, $q) {
                $query->where('shipping_name', 'like', "%$q%")
                      ->orWhere('shipping_phone', 'like', "%$q%");
            })
            ->whereNotNull('payment_slip') // มีสลิปเท่านั้น
            ->latest('order_date')
            ->paginate(10);

        return view('admin.payments.index', compact('orders', 'q'));
    }

    /** แสดงรายละเอียดคำสั่งซื้อแต่ละใบ */
    public function show(Order $order)
    {
        return view('admin.payments.show', compact('order'));
    }

    /** ✅ ยืนยันสลิป (สถานะ = verified) */
    public function verify(Order $order)
    {
        $order->update([
            'payment_status' => 'verified',
            'status' => 'paid',
        ]);

        return back()->with('status', '✅ ตรวจสอบสลิปเรียบร้อยแล้ว');
    }

    /** ❌ ปฏิเสธสลิป (สถานะ = rejected) */
    public function reject(Order $order)
    {
        $order->update([
            'payment_status' => 'rejected',
            'status' => 'cancelled',
        ]);

        return back()->with('status', '❌ ปฏิเสธสลิปเรียบร้อยแล้ว');
    }
}
