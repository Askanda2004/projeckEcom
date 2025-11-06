<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['order_id','amount','payment_date','method','status'];

    public function order() { return $this->belongsTo(Order::class, 'order_id', 'order_id'); }

    public function show($orderId)
    {
        $order = Order::with(['items.product'])->findOrFail($orderId);
        return view('admin.payments.show', compact('order'));
    }
}
