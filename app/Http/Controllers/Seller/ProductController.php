<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $q        = $request->string('q')->toString();
        $category = $request->string('category')->toString();

        // ✅ ใช้ guard ของผู้ขายให้ชัดเจน (fallback เป็น default guard เผื่อโปรเจกต์ไม่ได้ตั้ง guard 'seller')
        $sellerId = auth()->id();

        $products = Product::query()
            ->with(['images', 'category'])
            ->where('seller_id', $sellerId)                            // ✅ กรองตามร้านผู้ขายคนนั้น
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%")
                      ->orWhere('color', 'like', "%{$q}%")
                      ->orWhere('size', 'like', "%{$q}%");
                });
            })
            ->when($category !== '', function ($s) use ($category) {
                $s->whereHas('category', function ($q2) use ($category) {
                    $q2->where('category_name', 'like', "%{$category}%");
                });
            })
            ->latest()
            ->paginate(16)
            ->withQueryString();

        return view('seller.products.index', [
            'products' => $products,
            'q'        => $q,
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

            'images'          => ['nullable','array','max:10'],
            'images.*'        => ['image','mimes:jpg,jpeg,png,webp','max:4096'],

            'image'           => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ],[
            'images.*.image'  => 'ไฟล์รูปภาพไม่ถูกต้อง',
            'images.*.mimes'  => 'อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG, WEBP',
            'images.*.max'    => 'ขนาดรูปภาพต้องไม่เกิน 4MB ต่อไฟล์',
        ]);

        // ✅ ผูกสินค้าเข้ากับผู้ขายที่ล็อกอิน
        $sellerId = auth()->id();
        $data['seller_id'] = $sellerId;

        DB::transaction(function () use ($request, &$data) {
            if ($request->hasFile('image')) {
                $data['image_url'] = $request->file('image')->store('products','public');
            }

            /** @var \App\Models\Product $product */
            $product = Product::create($data);

            if ($request->hasFile('images')) {
                $order = 0;
                foreach ($request->file('images') as $idx => $file) {
                    $path = $file->store('products','public');
                    ProductImage::create([
                        'product_id' => $product->getKey(),
                        'path'       => $path,
                        'is_primary' => $idx === 0 && empty($product->image_url),
                        'ordering'   => $order++,
                    ]);
                }

                if (empty($product->image_url)) {
                    $first = $product->images()->orderBy('ordering')->first();
                    if ($first) {
                        $product->update(['image_url' => $first->path]);
                    }
                }
            }
        });

        return redirect()->route('seller.products.index')->with('status','เพิ่มสินค้าเรียบร้อย');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product); // policy ควรเช็ค seller_id ด้วย
        $categories = Category::orderBy('category_name')->get();
        $product->load('images');
        return view('seller.products.edit', compact('product','categories'));
    }

    public function update(Request $request, Product $product)
    {
        // ✅ ป้องกันไม่ให้แก้ของคนอื่น (ใช้ guard เดียวกับ index/store)
        $sellerId = auth()->id();
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

            'images'          => ['nullable','array','max:10'],
            'images.*'        => ['image','mimes:jpg,jpeg,png,webp','max:4096'],

            'image'           => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],

            'remove_image_ids'=> ['nullable','array'],
            'remove_image_ids.*' => ['integer','exists:product_images,id'],
        ]);

        DB::transaction(function () use ($request, $product, &$data) {
            if ($request->hasFile('image')) {
                if (!empty($product->image_url) && Storage::disk('public')->exists($product->image_url)) {
                    Storage::disk('public')->delete($product->image_url);
                }
                $data['image_url'] = $request->file('image')->store('products','public');
            }

            $product->update($data);

            if (is_array($request->input('remove_image_ids'))) {
                $toRemove = ProductImage::query()
                    ->whereIn('id', $request->input('remove_image_ids'))
                    ->where('product_id', $product->getKey())
                    ->get();

                foreach ($toRemove as $img) {
                    if ($img->path && Storage::disk('public')->exists($img->path)) {
                        Storage::disk('public')->delete($img->path);
                    }
                    $img->delete();
                }
            }

            if ($request->hasFile('images')) {
                $maxOrder = (int) ($product->images()->max('ordering') ?? -1);
                foreach ($request->file('images') as $file) {
                    $path = $file->store('products','public');
                    ProductImage::create([
                        'product_id' => $product->getKey(),
                        'path'       => $path,
                        'is_primary' => false,
                        'ordering'   => ++$maxOrder,
                    ]);
                }
            }

            if (!empty($product->image_url) && !Storage::disk('public')->exists($product->image_url)) {
                $first = $product->images()->orderBy('ordering')->first();
                if ($first) {
                    $product->update(['image_url' => $first->path]);
                } else {
                    $product->update(['image_url' => null]);
                }
            }
        });

        return back()->with('status','อัปเดตสินค้าเรียบร้อย');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->load('images');
        foreach ($product->images as $img) {
            if ($img->path && Storage::disk('public')->exists($img->path)) {
                Storage::disk('public')->delete($img->path);
            }
        }
        if (!empty($product->image_url) && Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete();
        return back()->with('status','ลบสินค้าแล้ว');
    }

    public function updateImage(Request $request, Product $product, ProductImage $image)
    {
        if ((int) $image->product_id !== (int) $product->product_id) {
            abort(404);
        }

        $validated = $request->validate([
            'replace_image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
            'set_primary'   => ['nullable', Rule::in(['1'])],
        ]);

        if ($request->hasFile('replace_image')) {
            $newPath = $request->file('replace_image')->store('products', 'public');
            if ($image->path) {
                Storage::disk('public')->delete($image->path);
            }
            $image->path = $newPath;
        }

        if (isset($validated['set_primary']) && $validated['set_primary'] === '1') {
            ProductImage::where('product_id', $product->product_id)->update(['is_primary' => false]);
            $image->is_primary = true;
        }

        $image->save();

        return back()->with('success', 'อัปเดตรูปภาพเรียบร้อย');
    }

    public function destroyImage(Product $product, ProductImage $image)
    {
        if ((int) $image->product_id !== (int) $product->product_id) {
            abort(404);
        }

        Storage::disk('public')->delete($image->path);

        $wasPrimary = $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $next = ProductImage::where('product_id', $product->product_id)->orderBy('ordering')->first();
            if ($next) {
                $next->is_primary = true;
                $next->save();
            }
        }

        return back()->with('success', 'ลบรูปภาพเรียบร้อย');
    }
}
