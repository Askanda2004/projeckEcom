<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ดึง category id มาจากตาราง categories
        $clothing = Category::where('category_name', 'เสื้อผ้า')->first();
        $shoes    = Category::where('category_name', 'รองเท้า')->first();

        $products = [
            [
                'name'           => 'เสื้อยืดลายมินิมอล',
                'description'    => 'เสื้อยืดผ้าฝ้าย 100% ใส่สบาย ระบายอากาศดี',
                'size'           => 'M',
                'price'          => 250.00,
                'stock_quantity' => 20,
                'category_id'    => $clothing?->category_id,
                'image_url'      => 'https://via.placeholder.com/150x150.png?text=T-Shirt',
            ],
            [
                'name'           => 'กางเกงยีนส์ Slim Fit',
                'description'    => 'กางเกงยีนส์ผ้ายืด ทรงเข้ารูป',
                'size'           => '32',
                'price'          => 890.00,
                'stock_quantity' => 15,
                'category_id'    => $clothing?->category_id,
                'image_url'      => 'https://via.placeholder.com/150x150.png?text=Jeans',
            ],
            [
                'name'           => 'รองเท้าผ้าใบคลาสสิก',
                'description'    => 'รองเท้าผ้าใบพื้นยาง ทนทาน ใส่ได้ทุกโอกาส',
                'size'           => '42',
                'price'          => 1200.00,
                'stock_quantity' => 10,
                'category_id'    => $shoes?->category_id,
                'image_url'      => 'https://via.placeholder.com/150x150.png?text=Sneakers',
            ],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
