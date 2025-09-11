<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim($request->get('q', ''));
        $catId    = $request->integer('category_id');
        $minPrice = $request->filled('min_price') ? (float) $request->get('min_price') : null;
        $maxPrice = $request->filled('max_price') ? (float) $request->get('max_price') : null;

        $products = Product::query()
            ->with('category')
            ->when($q !== '', fn($s) => $s->where(fn($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
            ))
            ->when($catId, fn($s) => $s->where('category_id', $catId))
            ->when(!is_null($minPrice), fn($s) => $s->where('price', '>=', $minPrice))
            ->when(!is_null($maxPrice), fn($s) => $s->where('price', '<=', $maxPrice))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('category_name')->get();

        return view('shop.products.index', compact('products', 'categories', 'q', 'catId', 'minPrice', 'maxPrice'));
    }

    public function show(Product $product)
    {
        $product->load('category');

        $related = Product::where('category_id', $product->category_id)
            ->where('product_id', '<>', $product->product_id)
            ->limit(4)
            ->get();

        return view('shop.products.show', compact('product', 'related'));
    }
}
