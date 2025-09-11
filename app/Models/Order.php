<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
            'user_id','seller_id','order_date','status','total_amount',
            'payment_id','shipping_name','shipping_phone','shipping_address',
            ];

    public function items()    { return $this->hasMany(\App\Models\OrderItem::class, 'order_id', 'order_id'); }
    public function payments() { return $this->hasMany(Payment::class, 'order_id', 'order_id'); }
    public function user()     { return $this->belongsTo(User::class, 'user_id', 'user_id'); } // ถ้า users.id ให้เปลี่ยนเป็น 'id'
}
