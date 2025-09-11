<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

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
}
