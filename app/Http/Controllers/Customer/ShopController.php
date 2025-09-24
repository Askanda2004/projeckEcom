<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;

class ShopController extends Controller
{
    /** หน้าร้าน (ใส่ของคุณเอง) */
    public function shop(Request $request)
    {
        $q = trim($request->get('q', ''));

        $products = Product::query()
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%")
                      ->orWhere('size', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%")
                      ->orWhere('color', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('product_id')   // ใช้คีย์จริงของตาราง
            ->paginate(12)
            ->withQueryString();

        return view('customer.shop', compact('products', 'q'));
    }

    /** ตะกร้า: โหลดจาก DB -> สร้าง array ให้ view + sync session เผื่อมีแก้ไขนอกหน้า cart */
    public function cart()
    {
        $cart = $this->userCart()->load(['items.product']);

        $sessionCart = [];
        foreach ($cart->items as $it) {
            $p = $it->product;
            if (!$p) continue;
            $sessionCart[$p->product_id] = [
                'name'      => $p->name,
                'price'     => (float) ($it->price ?? $p->price),
                'qty'       => (int) $it->quantity,
                'size'      => $p->size,
                'color'     => $p->color,
                'seller_id' => $p->seller_id,
                'image_url' => $p->image_url,
                'image'     => $p->image_url, // alias กัน view เก่า
            ];
        }
        session(['cart' => $sessionCart]);

        $total = collect($sessionCart)->sum(fn($r) => (float)($r['price'] ?? 0) * (int)($r['qty'] ?? 0));

        return view('customer.cart', [
            'cart'  => $sessionCart,
            'total' => $total,
        ]);
    }

    /** เพิ่มสินค้า (มีของคุณอยู่แล้ว แต่รวมไว้ให้ครบ flow) */
    public function addToCart(Request $request, Product $product)
    {
        $data = $request->validate([
            'qty' => ['required','integer','min:1']
        ]);
        $qty  = (int) $data['qty'];

        // ✅ เช็กจำนวนไม่ให้เกินสต็อก
        if ($qty > $product->stock_quantity) {
            return back()->with('status', 'จำนวนที่เลือกมากกว่าสต็อกที่มี')->withInput();
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

        // ✅ รวมกับของเดิมแล้วก็ต้องไม่เกินสต็อกเช่นกัน
        if ($item->quantity + $qty > $product->stock_quantity) {
            return back()->with('status', 'จำนวนรวมในตะกร้าเกินสต็อกที่มี')->withInput();
        }

        $item->quantity  = $item->quantity + $qty;
        $item->size      = $product->size;
        $item->color     = $product->color ?? null;
        $item->image_url = $product->image_url;
        $item->save();

        $this->refreshSessionCart($cart);

        return back()->with('status','เพิ่มสินค้าลงตะกร้าแล้ว');
    }


    /** ✅ อัปเดตจำนวนสินค้า: id = product_id */
    public function updateCart(Request $request, $id)
    {
        $data = $request->validate([
            'qty' => ['required','integer','min:1'],
        ]);
        $qty  = (int) $data['qty'];

        $cart = $this->userCart();

        $item = CartItem::where('cart_id', $cart->cart_id)
            ->where('product_id', $id)
            ->first();

        if (!$item) {
            // ถ้าไม่มี item แต่ user กรอกเลข ก็สร้างใหม่จาก product
            $product = Product::where('product_id', $id)->first();
            if (!$product) {
                return back()->with('error', 'ไม่พบสินค้าในระบบ');
            }
            $item = new CartItem();
            $item->cart_id    = $cart->cart_id;
            $item->product_id = $product->product_id;
            $item->price      = (float) $product->price;
            $item->size       = $product->size;
            $item->color      = $product->color;
            $item->image_url  = $product->image_url;
        }

        $item->quantity = $qty;
        $item->save();

        $this->refreshSessionCart($cart);

        return back()->with('status','อัปเดตจำนวนแล้ว');
    }

    /** ✅ ลบรายการหนึ่งชิ้น: id = product_id */
    public function removeFromCart(Request $request, $id)
    {
        $cart = $this->userCart();

        CartItem::where('cart_id', $cart->cart_id)
            ->where('product_id', $id)
            ->delete();

        $this->refreshSessionCart($cart);

        return back()->with('status','ลบรายการแล้ว');
    }

    /** ✅ ล้างตะกร้าทั้งหมดของ user */
    public function clearCart()
    {
        $cart = $this->userCart();

        CartItem::where('cart_id', $cart->cart_id)->delete();

        $this->refreshSessionCart($cart);

        return back()->with('status','ล้างตะกร้าแล้ว');
    }

    /** ยูทิลิตี้: คืน Cart ของผู้ใช้ (ถ้าไม่มีให้สร้าง) */
    protected function userCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()], []);
    }

    /** ยูทิลิตี้: sync DB cart -> session('cart') เพื่อให้หน้า view/checkout ใช้ข้อมูลล่าสุด */
    protected function refreshSessionCart(Cart $cart): void
    {
        $cart->load(['items.product']);

        $sessionCart = [];
        foreach ($cart->items as $it) {
            $p = $it->product;
            if (!$p) continue;

            $sessionCart[$p->product_id] = [
                'name'      => $p->name,
                'price'     => (float) ($it->price ?? $p->price),
                'qty'       => (int) $it->quantity,
                'size'      => $p->size,
                'color'     => $p->color,
                'seller_id' => $p->seller_id,
                'image_url' => $p->image_url,
                'image'     => $p->image_url, // alias กัน view เก่า
            ];
        }

        session(['cart' => $sessionCart]);
    }
}