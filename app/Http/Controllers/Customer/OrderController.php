<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // โหลดออเดอร์ของผู้ใช้คนนั้นเท่านั้น + items.product ไว้ดูรายละเอียด
        $orders = Order::query()
            ->with(['items.product'])
            ->where('user_id', $userId)
            ->latest('order_date')
            ->paginate(10)
            ->withQueryString();

        // mapping สถานะ -> ป้าย
        $statusMap = [
            'pending'    => 'รอดำเนินการ',
            'paid'       => 'ชำระเงินแล้ว',
            'processing' => 'กำลังจัดเตรียม',
            'shipped'    => 'จัดส่งแล้ว',
            'completed'  => 'สำเร็จ',
            'cancelled'  => 'ยกเลิก',
        ];

        return view('customer.orders.index', compact('orders','statusMap'));
    }
}
