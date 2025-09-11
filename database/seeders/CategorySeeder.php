<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category_name' => 'เสื้อผ้า'],
            ['category_name' => 'รองเท้า'],
            ['category_name' => 'กระเป๋า'],
            ['category_name' => 'เครื่องประดับ'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['category_name' => $cat['category_name']], $cat);
        }
    }
}
