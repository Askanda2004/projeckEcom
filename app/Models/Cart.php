<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $primaryKey = 'cart_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['user_id'];

    public function items() {
        return $this->hasMany(CartItem::class, 'cart_id', 'cart_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id'); // ถ้า users.id ให้เปลี่ยนเป็น 'id'
    }
}
