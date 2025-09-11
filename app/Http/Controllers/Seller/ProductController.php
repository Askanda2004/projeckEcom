<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    use AuthorizesRequests; 

    public function index(Request $request)
    {
        $q = trim($request->get('q',''));
        $category = trim($request->get('category',''));

        $pk = (new Product)->getKeyName();

        $products = Product::query()
            ->where('seller_id', auth()->id()) // ✅ เห็นเฉพาะของตัวเอง
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('name','like',"%{$q}%")
                      ->orWhere('description','like',"%{$q}%")
                      ->orWhere('color','like',"%{$q}%");
                });
            })
            ->when($category !== '', function ($s) use ($category) {
                $s->whereHas('category', function($q2) use ($category) {
                    $q2->where('category_name','like',"%{$category}%");
                });
            })
            ->with('category')
            ->orderByDesc($pk)
            ->paginate(10)
            ->withQueryString();

        return view('seller.products.index', [
            'products' => $products,
            'q' => $q,
            'category' => $category,
        ]);
    }

    public function create()
    {
        $categories = Category::orderBy('category_name')->get();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => ['required','string','max:255'],
            'description'     => ['nullable','string'],
            'size'            => ['nullable','string','max:50'],
            'color'           => ['nullable','string','max:50'],
            'price'           => ['required','numeric','min:0'],
            'stock_quantity'  => ['required','integer','min:0'],
            'category_id'     => ['nullable','integer','exists:categories,category_id'],
            'image'          => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('products','public');
        }

        // สำคัญ! ผูกสินค้าเข้ากับ seller คนที่กำลังล็อกอิน
        $data['seller_id'] = auth()->id();

        Product::create($data);
        // $product = \App\Models\Product::create($data);

        return redirect()->route('seller.products.index')->with('status','เพิ่มสินค้าเรียบร้อย');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product); // ✅ เจ้าของเท่านั้น
        $categories = Category::orderBy('category_name')->get();
        return view('seller.products.edit', compact('product','categories'));
    }

    public function update(Request $request, \App\Models\Product $product)
    {
        // ป้องกันไม่ให้แก้ของคนอื่น
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'name'            => ['required','string','max:255'],
            'description'     => ['nullable','string'],
            'size'            => ['nullable','string','max:100'],
            'color'           => ['nullable','string','max:100'],
            'price'           => ['required','numeric','min:0'],
            'stock_quantity'  => ['required','integer','min:0'],
            'category_id'     => ['nullable','integer','exists:categories,category_id'],
            'image'           => ['nullable','image','mimes:jpg,jpeg,png,webp'],
        ]);

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('products','public');
        }

        $product->update($data);

        return back()->with('status','อัปเดตสินค้าเรียบร้อย');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete(); // ถ้าใช้ SoftDeletes จะยิ่งปลอดภัย
        return back()->with('status','ลบสินค้าแล้ว');
    }
}
