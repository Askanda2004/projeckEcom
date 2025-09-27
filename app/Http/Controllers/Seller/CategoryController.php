<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = auth()->id();

        // ดึงหมวดหมู่ + นับจำนวนสินค้าของผู้ขายคนนี้
        $categories = Category::query()
            ->select(['category_id','category_name'])
            ->withCount(['products as products_count' => function($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            }])
            ->orderBy('category_name')
            ->get();

        return view('seller.categories.index', compact('categories'));
    }
}
