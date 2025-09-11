<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // ระบุชื่อ primary key ที่แท้จริง
    protected $primaryKey = 'product_id';

    // ถ้า primary key ไม่ใช่ auto-increment integer ให้บอกด้วย
    public $incrementing = true;

    // ถ้า primary key ไม่ใช่ big integer ให้เปลี่ยน type
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'description',
        'size',
        'color',
        'price',
        'stock_quantity',
        'category_id',
        'image_url',
        'seller_id'
    ];

    public function seller() 
    {
        return $this->belongsTo(User::class, 'seller_id', 'user_id');
    }

    public function getRouteKeyName(): string
    {
        return 'product_id';
    }

    public function category() 
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
    
    protected static function booted()
    {
        static::creating(function ($product) {
            if (!$product->seller_id && auth()->check()) {
                $product->seller_id = auth()->id();
            }
        });
    }
}
