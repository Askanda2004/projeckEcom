<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * รายการคำสั่งซื้อของร้าน (เห็นเฉพาะออเดอร์ที่มีสินค้า seller นี้)
     * รองรับค้นหา/กรองสถานะ และแสดงเฉพาะ items ของร้าน
     */
    public function index(Request $request)
    {
        $sellerId = auth()->id();

        $q      = (string) $request->query('q', '');
        $status = (string) $request->query('status', ''); // pending/paid/shipped/completed/cancelled

        $orders = Order::query()
            // ต้องมี item ที่เป็นของ seller คนนี้อย่างน้อย 1 รายการ
            ->whereHas('items', function ($sub) use ($sellerId) {
                $sub->where('seller_id', $sellerId);
            })
            // ค้นหาจากชื่อ/เบอร์/ที่อยู่
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('shipping_name', 'like', "%{$q}%")
                      ->orWhere('shipping_phone', 'like', "%{$q}%")
                      ->orWhere('shipping_address', 'like', "%{$q}%");
                });
            })
            // กรองสถานะ (ถ้าระบุ)
            ->when($status !== '', function ($s) use ($status) {
                $s->where('status', $status);
            })
            // ✅ โหลดเฉพาะ items ของ seller นี้ + product และโหลด payment มาด้วย
            ->with([
                'items' => function ($q2) use ($sellerId) {
                    $q2->where('seller_id', $sellerId)->with('product');
                },
                'user',
                'payment', // ✅ สำคัญ: ให้ Blade อ่าน $o->payment->status / slip_path
            ])
            ->latest('order_id')
            ->paginate(15)
            ->withQueryString();

        // คำนวณยอดรวมเฉพาะสินค้าของร้านนี้ (เพื่อแสดงที่ตารางได้เลย)
        $orders->getCollection()->transform(function ($o) {
            $o->seller_subtotal = $o->items->sum(fn ($it) => (float) $it->price * (int) $it->quantity);
            return $o;
        });

        // map สถานะเพื่อใช้กับ dropdown ใน Blade
        $statusMap = [
            'pending'   => 'รอตรวจสอบ',
            'paid'      => 'ชำระแล้ว',
            'shipped'   => 'จัดส่งแล้ว',
            'completed' => 'สำเร็จ',
            'cancelled' => 'ยกเลิก',
        ];

        return view('seller.orders.index', compact('orders', 'q', 'status', 'statusMap'));
    }

    /**
     * รายละเอียดคำสั่งซื้อ (แสดงเฉพาะ items ของร้านนี้)
     */
    public function show($orderId)
    {
        $sellerId = auth()->id();

        $order = Order::query()
            ->whereHas('items', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            })
            ->with([
                'items' => function ($q) use ($sellerId) {
                    $q->where('seller_id', $sellerId)->with('product');
                },
                'user',
                'payment', // ✅ โหลดสถานะการชำระเงิน/สลิป
            ])
            ->findOrFail($orderId);

        // ยอดรวมของร้านนี้ในออเดอร์นี้
        $order->seller_subtotal = $order->items->sum(fn ($it) => (float) $it->price * (int) $it->quantity);

        $statusMap = [
            'pending'   => 'รอตรวจสอบ',
            'paid'      => 'ชำระแล้ว',
            'shipped'   => 'จัดส่งแล้ว',
            'completed' => 'สำเร็จ',
            'cancelled' => 'ยกเลิก',
        ];

        return view('seller.orders.show', compact('order', 'statusMap'));
    }

    /**
     * เปลี่ยนสถานะออเดอร์ (ปุ่ม select ในตาราง)
     * ถ้าต้องการบังคับให้ส่งของได้ก็ต่อเมื่อชำระเงิน verified แล้ว
     * ให้ปลดคอมเมนต์บล็อกตรวจด้านล่าง
     */
    public function updateStatus(Request $request, Order $order)
    {
        $sellerId = auth()->id();

        // ป้องกัน seller ที่ไม่มีสินค้าของตัวเองในออเดอร์นี้
        $has = $order->items()->where('seller_id', $sellerId)->exists();
        if (!$has) {
            abort(403);
        }

        $data = $request->validate([
            'status' => ['required', Rule::in(['pending','paid','shipped','completed','cancelled'])],
        ]);

        // ✅ (ทางเลือก) บังคับให้ "shipped/completed" ได้เมื่อชำระเงิน verified แล้วเท่านั้น
        /*
        $order->loadMissing('payment');
        $payStatus = $order->payment->status ?? 'unpaid'; // unpaid|pending|verified|rejected
        if (in_array($data['status'], ['shipped','completed']) && $payStatus !== 'verified') {
            return back()->with('status', 'ยังไม่สามารถเปลี่ยนเป็นจัดส่ง/สำเร็จได้ จนกว่าจะชำระเงินสำเร็จ');
        }
        */

        $order->status = $data['status'];
        $order->save();

        return back()->with('status', 'อัปเดตสถานะคำสั่งซื้อแล้ว');
    }
}
