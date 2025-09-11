<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $primaryKey = 'cart_item_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'cart_id','product_id','quantity','price','size','color','image_url'
    ];

    public function cart() {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
