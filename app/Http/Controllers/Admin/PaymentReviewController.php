<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class PaymentReviewController extends Controller
{
    // รายการคำสั่งซื้อที่มีสลิป รอสถานะตรวจสอบ/จ่ายแล้ว
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q',''));

        $orders = Order::query()
            ->with(['items']) // ต้องมีสัมพันธ์ items ในโมเดล Order
            ->when($q !== '', function($s) use ($q) {
                $s->where('shipping_name','like',"%{$q}%")
                  ->orWhere('shipping_phone','like',"%{$q}%");
            })
            ->whereNotNull('payment_slip')  // มีสลิปเท่านั้น
            ->orderByDesc('order_id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.payments.index', compact('orders','q'));
    }

    public function show(Order $order)
    {
        $order->load(['items']); // แสดงรายละเอียดรายการ
        return view('admin.payments.show', compact('order'));
    }

    public function approve(Order $order)
    {
        // อนุมัติ => เปลี่ยนเป็น paid
        $order->update(['status' => 'paid']);

        // TODO: หากต้องการแจ้งเตือน seller/ลูกค้า สามารถยิง event/notification ที่นี่
        // เช่น dispatch(new NotifySellersPaid($order));

        return back()->with('status','ยืนยันการชำระเงินแล้ว (paid)');
    }

    public function reject(Order $order)
    {
        // ไม่ผ่าน => กลับเป็น pending และลบสลิป (หรือเก็บไว้ก็ได้)
        $order->update(['status' => 'pending']);
        return back()->with('status','ปฏิเสธการชำระเงินแล้ว (กลับไป pending)');
    }
}
