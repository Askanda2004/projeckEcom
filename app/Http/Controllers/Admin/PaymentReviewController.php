<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentReviewController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->query('q','');

        $orders = Order::query()
            ->when($q !== '', function($s) use ($q) {
                $s->where(function($w) use ($q) {
                    $w->where('shipping_name','like',"%{$q}%")
                      ->orWhere('shipping_phone','like',"%{$q}%");
                });
            })
            ->whereNotNull('payment_slip') // มีสลิปเท่านั้น
            ->latest('order_id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.payments.index', compact('orders','q'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product','user']);
        return view('admin.payments.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'action' => ['required','in:verify,reject'],
        ]);

        if ($data['action'] === 'verify') {
            $order->payment_status     = 'verified';
            $order->payment_verified_by= auth()->id();
            $order->payment_verified_at= now();
        } else {
            $order->payment_status     = 'rejected';
            $order->payment_verified_by= auth()->id();
            $order->payment_verified_at= now();
        }
        $order->save();

        return redirect()->route('admin.payments.index')->with('status','อัปเดตสถานะการชำระเงินเรียบร้อย');
    }
}
