<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
            'user_id','seller_id','order_date','status',
            'payment_slip','payment_status',
            'total_amount','shipping_name','shipping_phone','shipping_address',
            ];

    public function items()    { return $this->hasMany(\App\Models\OrderItem::class, 'order_id', 'order_id'); }
    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class, 'order_id', 'order_id');
    }
    // public function payments() { return $this->hasMany(Payment::class, 'order_id', 'order_id'); }
    public function user()     { return $this->belongsTo(User::class, 'user_id', 'user_id'); } // ถ้า users.id ให้เปลี่ยนเป็น 'id'
    protected $casts = [
        'order_date' => 'datetime',
    ];
    public function scopePaid($q)     { return $q->where('payment_status','verified'); }
    public function scopePendingPay($q){ return $q->where('payment_status','pending'); }
}