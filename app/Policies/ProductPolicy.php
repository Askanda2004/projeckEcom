<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\View;
use App\Models\SellerProfile;

class ProductPolicy
{
    public function view(User $user, Product $product): bool
    {
        return $product->seller_id === $user->user_id || $user->role === 'admin';
    }

    public function update(User $user, Product $product): bool
    {
        return $product->seller_id === $user->user_id || $user->role === 'admin';
    }

    public function delete(User $user, Product $product): bool
    {
        return $product->seller_id === $user->user_id || $user->role === 'admin';
    }

    public function boot(): void
    {
        // แชร์โปรไฟล์ร้านให้ทุกวิวที่ขึ้นต้นด้วย seller.
        View::composer('seller.*', function ($view) {
            $profile = null;
            if (auth()->check()) {
                $profile = SellerProfile::where('user_id', auth()->id())->first();
            }
            $view->with('sidebarProfile', $profile);
        });
    }
}
