<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();

        $products = Product::query()
            ->with(['images','category'])
            ->when($q, fn($qr) =>
                $qr->where('name', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%")
            )
            ->latest()
            ->paginate(16)
            ->withQueryString();

        return view('customer.shop', compact('products', 'q'));
    }

    public function show(Product $product)
    {
        $product->load([
            'images'   => fn($q) => $q->orderByDesc('is_primary')->orderBy('ordering'),
            'category'
        ]);

        // สินค้าที่เกี่ยวข้อง (อย่างง่าย)
        $related = Product::with(['images'])
            ->where('product_id', '!=', $product->product_id)
            ->when($product->category_id, fn($q) => $q->where('category_id', $product->category_id))
            ->latest()->take(8)->get();

        return view('customer.product-show', compact('product','related'));
    }
}
