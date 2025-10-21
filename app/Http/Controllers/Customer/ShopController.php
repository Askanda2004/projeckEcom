<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;

class ShopController extends Controller
{
    public function shop(Request $request)
    {
        $q           = trim($request->get('q', ''));
        $categoryId  = $request->filled('category') ? (int) $request->get('category') : null;

        $categories = Category::query()
            ->withCount(['products as products_count' => function ($s) use ($q) {
                if ($q !== '') {
                    $s->where(function ($w) use ($q) {
                        $w->where('name','like',"%{$q}%")
                          ->orWhere('description','like',"%{$q}%")
                          ->orWhere('size','like',"%{$q}%")
                          ->orWhere('color','like',"%{$q}%");
                    });
                }
            }])
            ->orderBy('category_name')
            ->get();

        $products = Product::query()
            ->with(['images','category'])
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('name','like',"%{$q}%")
                      ->orWhere('description','like',"%{$q}%")
                      ->orWhere('size','like',"%{$q}%")
                      ->orWhere('color','like',"%{$q}%");
                });
            })
            ->when($categoryId, fn($s) => $s->where('category_id', $categoryId))
            ->latest()
            ->paginate(16)
            ->withQueryString();

        return view('customer.shop', [
            'products'        => $products,
            'categories'      => $categories,
            'currentCategory' => $categoryId,
            'q'               => $q,
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['images','category']);
        $related = Product::query()
            ->where('product_id','!=',$product->product_id)
            ->when($product->category_id, fn($q)=>$q->where('category_id',$product->category_id))
            ->latest()->take(8)
            ->with(['images'])
            ->get();

        return view('customer.product-show', compact('product','related'));
    }

    /** ตะกร้า: โหลดจาก DB -> สร้าง array ให้ view + sync session เผื่อมีแก้ไขนอกหน้า cart */
    public function cart()
    {
        $cart = $this->userCart()->load(['items.product']);

        $sessionCart = [];
        foreach ($cart->items as $it) {
            $p = $it->product;
            if (!$p) continue;

            // ⚠️ เก็บสต็อกใส่ลง session cart ด้วย
            $stock = max(0, (int)($p->stock_quantity ?? 0));

            $sessionCart[$p->product_id] = [
                'name'      => $p->name,
                'price'     => (float) ($it->price ?? $p->price),
                'qty'       => (int) $it->quantity,
                'size'      => $p->size,
                'color'     => $p->color,
                'seller_id' => $p->seller_id,
                'image_url' => $p->image_url,
                'image'     => $p->image_url, // alias กัน view เก่า
                'stock'     => $stock,        // ✅ ใส่สต็อก
            ];
        }
        session(['cart' => $sessionCart]);

        $total = collect($sessionCart)->sum(fn($r) => (float)($r['price'] ?? 0) * (int)($r['qty'] ?? 0));

        return view('customer.cart', [
            'cart'  => $sessionCart,
            'total' => $total,
        ]);
    }

    /** เพิ่มสินค้า */
    public function addToCart(Request $request, Product $product)
    {
        $data = $request->validate([
            'qty' => ['required','integer','min:1']
        ]);
        $qty  = (int) $data['qty'];

        $stock = max(0, (int)($product->stock_quantity ?? 0));
        if ($stock <= 0) {
            return back()->with('error', 'สินค้าหมดสต็อกแล้ว');
        }

        if ($qty > $stock) {
            return back()->with('error', "จำนวนที่เลือกเกินสต็อก สูงสุดคือ {$stock} ชิ้น")->withInput();
        }

        $cart = $this->userCart();

        // upsert รายการ
        $item = CartItem::firstOrNew([
            'cart_id'    => $cart->cart_id,
            'product_id' => $product->product_id,
        ]);

        if (!$item->exists) {
            $item->price = (float) $product->price; // snapshot ครั้งแรก
            $item->quantity = 0;
        }

        // รวมของเดิม + ใหม่ ก็ยังต้องไม่เกินสต็อก
        if ($item->quantity + $qty > $stock) {
            return back()->with('error', "จำนวนรวมในตะกร้าเกินสต็อก สูงสุดคือ {$stock} ชิ้น");
        }

        $item->quantity  = $item->quantity + $qty;
        $item->size      = $product->size;
        $item->color     = $product->color ?? null;
        $item->image_url = $product->image_url;
        $item->save();

        $this->refreshSessionCart($cart);

        return back()->with('status','เพิ่มสินค้าลงตะกร้าแล้ว');
    }

    /** อัปเดตจำนวนสินค้า: id = product_id */
    public function updateCart(Request $request, $id)
    {
        $data = $request->validate([
            'qty' => ['required','integer','min:1'],
        ]);
        $qty  = (int) $data['qty'];

        $cart = $this->userCart();

        $product = Product::where('product_id', $id)->first();
        if (!$product) {
            return back()->with('error', 'ไม่พบสินค้าในระบบ');
        }

        $stock = max(0, (int)($product->stock_quantity ?? 0));
        if ($stock <= 0) {
            // ถ้าสต็อกหมด ให้ตั้งเป็น 0 หรือเอาออกจากตะกร้าเลยก็ได้
            CartItem::where('cart_id', $cart->cart_id)->where('product_id', $id)->delete();
            $this->refreshSessionCart($cart);
            return back()->with('error','สินค้าหมดสต็อก รายการถูกนำออกจากตะกร้าแล้ว');
        }

        // clamp ไม่ให้เกินสต็อก
        if ($qty > $stock) {
            $qty = $stock;
            session()->flash('error', "จำนวนที่เลือกเกินสต็อก สูงสุดคือ {$stock} ชิ้น");
        }

        $item = CartItem::firstOrNew([
            'cart_id'    => $cart->cart_id,
            'product_id' => $product->product_id,
        ]);

        if (!$item->exists) {
            $item->price     = (float) $product->price;
            $item->size      = $product->size;
            $item->color     = $product->color;
            $item->image_url = $product->image_url;
        }

        $item->quantity = $qty;
        $item->save();

        $this->refreshSessionCart($cart);

        return back()->with('status','อัปเดตจำนวนแล้ว');
    }

    /** ลบรายการหนึ่งชิ้น: id = product_id */
    public function removeFromCart(Request $request, $id)
    {
        $cart = $this->userCart();

        CartItem::where('cart_id', $cart->cart_id)
            ->where('product_id', $id)
            ->delete();

        $this->refreshSessionCart($cart);

        return back()->with('status','ลบรายการแล้ว');
    }

    /** ล้างตะกร้าทั้งหมดของ user */
    public function clearCart()
    {
        $cart = $this->userCart();

        CartItem::where('cart_id', $cart->cart_id)->delete();

        $this->refreshSessionCart($cart);

        return back()->with('status','ล้างตะกร้าแล้ว');
    }

    /** คืน Cart ของผู้ใช้ (ถ้าไม่มีให้สร้าง) */
    protected function userCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()], []);
    }

    /** sync DB cart -> session('cart') */
    protected function refreshSessionCart(Cart $cart): void
    {
        $cart->load(['items.product']);

        $sessionCart = [];
        foreach ($cart->items as $it) {
            $p = $it->product;
            if (!$p) continue;

            $stock = max(0, (int)($p->stock_quantity ?? 0));

            $sessionCart[$p->product_id] = [
                'name'      => $p->name,
                'price'     => (float) ($it->price ?? $p->price),
                'qty'       => (int) $it->quantity,
                'size'      => $p->size,
                'color'     => $p->color,
                'seller_id' => $p->seller_id,
                'image_url' => $p->image_url,
                'image'     => $p->image_url,
                'stock'     => $stock, // ✅ ให้ Blade ใช้เป็น data-max
            ];
        }

        session(['cart' => $sessionCart]);
    }
}
