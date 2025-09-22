<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserManagementController;

use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ReportController;
use App\Http\Controllers\Seller\ProductController;

use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;

// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\Shop\ProductBrowseController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

// Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // เฉพาะ Admin
    // ✅ กลุ่มสำหรับผู้ดูแลระบบ
    Route::middleware(['auth','verified','role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // หน้าแรกของแอดมิน = หน้า Manage Users (ทั้งหมด)
        Route::get('/',                 [UserManagementController::class, 'index'])->name('index');

        // หน้า "Manage Users (ทั้งหมด)"—สามารถเข้าทาง /admin/users ก็ได้
        Route::get('/users',            [UserManagementController::class, 'index'])->name('users.index');

        // ✅ หน้าแสดงผู้ใช้ตาม role: customer / seller / admin
        Route::get('/users/role/{role}', [UserManagementController::class, 'byRole'])
            ->whereIn('role', ['customer','seller','admin'])
            ->name('users.byRole');

        // (ทางเลือก) แก้ไข/อัปเดต/ลบ ผู้ใช้ (คงของเดิมไว้)
        Route::get   ('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::patch ('/users/{user}',      [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',      [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    // เฉพาะ Seller
    Route::middleware(['auth','verified','role:seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {

        // Dashboard ของ Seller
        Route::get('/', fn () => view('seller.dashboard'))->name('index');

        /*
        |---------------------------
        | Product Management (ของฉัน)
        |---------------------------
        | ใช้ {product:product_id} เพื่อ bind ด้วยคอลัมน์ product_id
        */
        Route::get   ('/products',                             [SellerProductController::class, 'index'])->name('products.index');
        Route::get   ('/products/create',                      [SellerProductController::class, 'create'])->name('products.create');
        Route::post  ('/products',                             [SellerProductController::class, 'store'])->name('products.store');
        Route::get   ('/products/{product:product_id}/edit',   [SellerProductController::class, 'edit'])->name('products.edit');
        Route::patch ('/products/{product:product_id}',        [SellerProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product:product_id}',        [SellerProductController::class, 'destroy'])->name('products.destroy');

        Route::patch('products/{product}/images/{image}', [ProductController::class, 'updateImage'])
            ->name('products.images.update');     // แทนที่ไฟล์ + ตั้งเป็นรูปหลัก
        Route::delete('products/{product}/images/{image}', [ProductController::class, 'destroyImage'])
            ->name('products.images.destroy');    // ลบรูป

        /*
        |---------------------------
        | Order Management (ของฉัน)
        |---------------------------
        | ใช้ {order:order_id} เพื่อ bind ด้วยคอลัมน์ order_id
        */
        Route::get   ('/orders',                                [SellerOrderController::class, 'index'])->name('orders.index');
        // ดูรายละเอียดออเดอร์ (สินค้าในออเดอร์)
        Route::get   ('/orders/{order:order_id}',               [SellerOrderController::class, 'show'])->name('orders.show');
        // เปลี่ยนสถานะออเดอร์
        Route::patch ('/orders/{order:order_id}/status',        [SellerOrderController::class, 'updateStatus'])->name('orders.status');

        // (ออปชัน) เผื่อโฮสต์บางที่ block PATCH/DELETE — เพิ่มสำรองเป็น POST
        Route::post  ('/orders/{order:order_id}/status',        [SellerOrderController::class, 'updateStatus'])->name('orders.status.post');

        // รายงานยอดขาย
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });


    // เฉพาะ Customer
    Route::middleware(['auth','verified','role:customer'])
    ->prefix('customer')->name('customer.')
    ->group(function () {
        // Route::get('/shop',  [ShopController::class, 'shop'])->name('shop');
        Route::get('/shop', [CustomerProductController::class, 'index'])->name('shop');

        Route::get('/cart',  [ShopController::class, 'cart'])->name('cart');

        Route::post('/cart/add/{product:product_id}', [ShopController::class, 'addToCart'])->name('cart.add');

        Route::patch('/cart/update/{id}',  [ShopController::class, 'updateCart'])->name('cart.update');
        Route::delete('/cart/remove/{id}', [ShopController::class, 'removeFromCart'])->name('cart.remove');
        Route::delete('/cart/clear',       [ShopController::class, 'clearCart'])->name('cart.clear');

        Route::post('/cart/update/{id}',  [ShopController::class, 'updateCart'])->name('cart.update.post');
        Route::post('/cart/remove/{id}',  [ShopController::class, 'removeFromCart'])->name('cart.remove.post');
        Route::post('/cart/clear-all',    [ShopController::class, 'clearCart'])->name('cart.clear.post');

        // ✅ Checkout (อ่าน session cart + สร้างออเดอร์)
        Route::get('/checkout',  [CheckoutController::class, 'create'])->name('checkout');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.place');
        // Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        Route::get('/products/{product:product_id}', [CustomerProductController::class, 'show'])
        ->name('products.show');
    });
    // Route::middleware(['auth','verified','role:customer'])
    // ->prefix('customer')
    // ->name('customer.')
    // ->group(function () {

    //     // หน้าร้าน + ตะกร้า (คงของเดิม)
    //     Route::get('/shop',                [ShopController::class, 'shop'])->name('shop');
    //     Route::get('/cart',                [ShopController::class, 'cart'])->name('cart');
    //     Route::post('/cart/add/{product_id}', [ShopController::class, 'addToCart'])->name('cart.add');
    //     Route::patch('/cart/update/{id}',  [ShopController::class, 'updateCart'])->name('cart.update');
    //     Route::delete('/cart/remove/{id}', [ShopController::class, 'removeFromCart'])->name('cart.remove');
    //     Route::delete('/cart/clear',       [ShopController::class, 'clearCart'])->name('cart.clear');

    //     // Checkout (Controller เดียว)
    //     Route::get('/checkout',  [CheckoutController::class, 'create'])->name('checkout');
    //     Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    // });
    

    // อนุญาตหลาย role
    Route::middleware('role:admin,seller')->group(function () {
        Route::get('/manage-products', fn() => 'Manage Products')->name('manage.products');
    });
});

require __DIR__.'/auth.php';

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// require __DIR__.'/auth.php';