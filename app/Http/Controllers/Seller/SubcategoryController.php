<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = auth()->id();

        // โหลดหมวด + สินค้าของผู้ขายรายนี้
        $categories = Category::query()
            ->with(['products' => function($q) use ($sellerId) {
                $q->where('seller_id', $sellerId)
                  ->select('product_id','image_url','name','description','size','color','price','stock_quantity','category_id');
            }])
            ->orderBy('category_name')
            ->get();

        return view('seller.subcategories.index', compact('categories'));
    }
}
