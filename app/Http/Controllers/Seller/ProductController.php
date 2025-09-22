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

        $products = \App\Models\Product::query()
            ->with(['images', 'category']) // ✅ โหลดรูปและหมวดหมู่
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
            ->latest()               // ->orderByDesc('created_at')
            ->paginate(16)
            ->withQueryString();

        return view('customer.shop', [
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
        // รองรับทั้งแบบเดิม (image เดี่ยว) และแบบใหม่ (images หลายรูป)
        $data = $request->validate([
            'name'            => ['required','string','max:255'],
            'description'     => ['nullable','string'],
            'size'            => ['nullable','string','max:50'],
            'color'           => ['nullable','string','max:50'],
            'price'           => ['required','numeric','min:0'],
            'stock_quantity'  => ['required','integer','min:0'],
            'category_id'     => ['nullable','integer','exists:categories,category_id'],

            // แบบใหม่หลายรูป
            'images'          => ['nullable','array','max:10'],        // จำกัดสูงสุด 10 รูป
            'images.*'        => ['image','mimes:jpg,jpeg,png,webp','max:4096'], // 4MB/ไฟล์

            // แบบเดิมรูปเดียว (ถ้ายังส่งมา)
            'image'           => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ],[
            'images.*.image'  => 'ไฟล์รูปภาพไม่ถูกต้อง',
            'images.*.mimes'  => 'อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG, WEBP',
            'images.*.max'    => 'ขนาดรูปภาพต้องไม่เกิน 4MB ต่อไฟล์',
        ]);

        $data['seller_id'] = auth()->id();

        DB::transaction(function () use ($request, &$data) {
            // ถ้ามีส่ง image เดี่ยวมา (โหมดเก่า) เก็บเป็น image_url ไว้เป็นภาพหลัก
            if ($request->hasFile('image')) {
                $data['image_url'] = $request->file('image')->store('products','public');
            }

            /** @var \App\Models\Product $product */
            $product = Product::create($data);

            // โหมดใหม่: เก็บหลายไฟล์ใน product_images (รูปแรกเป็น primary)
            if ($request->hasFile('images')) {
                $order = 0;
                foreach ($request->file('images') as $idx => $file) {
                    $path = $file->store('products','public');

                    ProductImage::create([
                        'product_id' => $product->getKey(),
                        'path'       => $path,
                        'is_primary' => $idx === 0 && empty($product->image_url), // ถ้าไม่มี image_url เดิม ให้รูปแรกเป็นหลัก
                        'ordering'   => $order++,
                    ]);
                }

                // ถ้าไม่มี image_url เดิม ให้ sync ภาพหลักจาก product_images แรก (เผื่อฝั่ง frontend ยังอ่านจาก image_url)
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
        $this->authorize('update', $product); // ✅ เจ้าของเท่านั้น
        $categories = Category::orderBy('category_name')->get();
        // แนะนำให้ eager load รูปไว้ใช้ในฟอร์มแก้ไข
        $product->load('images');
        return view('seller.products.edit', compact('product','categories'));
    }

    public function update(Request $request, Product $product)
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

            // เพิ่มรูปใหม่หลายไฟล์ได้
            'images'          => ['nullable','array','max:10'],
            'images.*'        => ['image','mimes:jpg,jpeg,png,webp','max:4096'],

            // โหมดเก่ารูปเดียว (ถัดไปยังรองรับอยู่)
            'image'           => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],

            // ถ้าฟอร์มแก้ไขมี checkbox/hidden ส่ง id ของรูปที่จะลบ
            'remove_image_ids'=> ['nullable','array'],
            'remove_image_ids.*' => ['integer','exists:product_images,id'],
        ]);

        DB::transaction(function () use ($request, $product, &$data) {
            // ถ้ามีรูปเดี่ยวใหม่มา อัปเดต image_url (primary) ทับของเดิม
            if ($request->hasFile('image')) {
                // ลบไฟล์เก่าที่เคยเก็บใน image_url (ถ้าอยู่ใน storage)
                if (!empty($product->image_url) && Storage::disk('public')->exists($product->image_url)) {
                    Storage::disk('public')->delete($product->image_url);
                }
                $data['image_url'] = $request->file('image')->store('products','public');
            }

            $product->update($data);

            // ลบรูปที่เลือก
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

            // เพิ่มรูปใหม่ (หลายไฟล์)
            if ($request->hasFile('images')) {
                // หา ordering ล่าสุด
                $maxOrder = (int) ($product->images()->max('ordering') ?? -1);
                foreach ($request->file('images') as $file) {
                    $path = $file->store('products','public');
                    ProductImage::create([
                        'product_id' => $product->getKey(),
                        'path'       => $path,
                        'is_primary' => false,         // ไม่เปลี่ยน primary อัตโนมัติ
                        'ordering'   => ++$maxOrder,
                    ]);
                }
            }

            // ถ้าหลังลบรูป/เพิ่มรูป แล้ว image_url ชี้ไปไฟล์ที่หายไป ให้ซ่อมให้ชี้ไปภาพแรกที่เหลือ
            if (!empty($product->image_url) && !Storage::disk('public')->exists($product->image_url)) {
                $first = $product->images()->orderBy('ordering')->first();
                if ($first) {
                    $product->update(['image_url' => $first->path]);
                } else {
                    // ไม่มีรูปเหลือแล้ว
                    $product->update(['image_url' => null]);
                }
            }
        });

        return back()->with('status','อัปเดตสินค้าเรียบร้อย');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        // ลบไฟล์รูปทั้งหมดใน storage ก่อน (แม้ FK จะ cascade ลบ record ให้อยู่แล้ว)
        $product->load('images');
        foreach ($product->images as $img) {
            if ($img->path && Storage::disk('public')->exists($img->path)) {
                Storage::disk('public')->delete($img->path);
            }
        }
        if (!empty($product->image_url) && Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete(); // ถ้าใช้ SoftDeletes จะยิ่งปลอดภัย
        return back()->with('status','ลบสินค้าแล้ว');
    }

    public function updateImage(Request $request, Product $product, ProductImage $image)
    {
        // ป้องกันกรณีส่ง image ที่ไม่ใช่ของ product นี้
        if ((int) $image->product_id !== (int) $product->product_id) {
            abort(404);
        }

        $validated = $request->validate([
            'replace_image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'], // 4MB
            'set_primary'   => ['nullable', Rule::in(['1'])],
        ]);

        // ถ้ามีไฟล์ใหม่ -> เก็บและลบไฟล์เก่า
        if ($request->hasFile('replace_image')) {
            $newPath = $request->file('replace_image')->store('products', 'public');

            // ลบไฟล์เก่าจาก storage
            if ($image->path) {
                \Storage::disk('public')->delete($image->path);
            }

            $image->path = $newPath;
        }

        // ตั้งเป็นรูปหลัก (ถ้าติ๊ก)
        if (isset($validated['set_primary']) && $validated['set_primary'] === '1') {
            // เคลียร์รูปหลักตัวอื่นของสินค้านี้
            ProductImage::where('product_id', $product->product_id)->update(['is_primary' => false]);
            $image->is_primary = true;
        }

        $image->save();

        return back()->with('success', 'อัปเดตรูปภาพเรียบร้อย');
    }

    /**
     * ลบรูปภาพเดิมออกจากสินค้า
     */
    public function destroyImage(Product $product, ProductImage $image)
    {
        if ((int) $image->product_id !== (int) $product->product_id) {
            abort(404);
        }

        // ลบไฟล์จริง
        \Storage::disk('public')->delete($image->path);

        $wasPrimary = $image->is_primary;
        $image->delete();

        // ถ้าลบรูปหลักทิ้ง ให้ตั้งรูปตัวแรกที่เหลือเป็นหลัก (ถ้ามี)
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
