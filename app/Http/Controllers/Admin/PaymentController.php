<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /** 📋 แสดงรายการคำสั่งซื้อทั้งหมดที่มีสลิป */
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));

        $orders = Order::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($s) use ($q) {
                    $s->where('shipping_name', 'like', "%{$q}%")
                      ->orWhere('shipping_phone', 'like', "%{$q}%")
                      ->orWhere('order_id', 'like', "%{$q}%");
                });
            })
            ->whereNotNull('payment_slip')  // แสดงเฉพาะที่มีสลิป
            ->latest('order_date')
            ->paginate(10)
            ->withQueryString();

        return view('admin.payments.index', compact('orders', 'q'));
    }

    /** 🔍 แสดงรายละเอียดคำสั่งซื้อและสลิป */
    public function show($id)
    {
        // โหลดข้อมูล + รายการสินค้า + สินค้าแต่ละชิ้น
        $order = Order::with(['items.product'])
            ->findOrFail($id);

        return view('admin.payments.show', compact('order'));
    }

    /** ✅ ยืนยันสลิป (เปลี่ยนสถานะเป็น verified / paid) */
    public function verify($id)
    {
        $order = Order::findOrFail($id);

        try {
            $order->update([
                'payment_status' => 'verified',
                'status'          => 'paid',
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', 'ไม่สามารถยืนยันการชำระเงินได้');
        }

        return redirect()
            ->route('admin.payments.index')
            ->with('status', '✅ ตรวจสอบสลิปเรียบร้อยแล้ว');
    }

    /** ❌ ปฏิเสธสลิป (เปลี่ยนสถานะเป็น rejected / cancelled) */
    public function reject($id)
    {
        $order = Order::findOrFail($id);

        try {
            $order->update([
                'payment_status' => 'rejected',
                'status'          => 'cancelled',
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', 'ไม่สามารถปฏิเสธสลิปได้');
        }

        return redirect()
            ->route('admin.payments.index')
            ->with('status', '❌ ปฏิเสธสลิปเรียบร้อยแล้ว');
    }
}
