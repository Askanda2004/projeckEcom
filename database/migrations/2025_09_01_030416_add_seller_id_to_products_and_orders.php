<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // เพิ่ม seller_id ให้ products
        if (!Schema::hasColumn('products', 'seller_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('seller_id')->nullable()->after('product_id');
                $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            });
            
            DB::table('products')->whereNull('seller_id')->update(['seller_id' => 1]);
        }

        // เพิ่ม seller_id ให้ orders
        if (!Schema::hasColumn('orders', 'seller_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('seller_id')->nullable()->after('user_id');
                $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'seller_id')) {
                $table->dropForeign(['seller_id']);
                $table->dropColumn('seller_id');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'seller_id')) {
                $table->dropForeign(['seller_id']);
                $table->dropColumn('seller_id');
            }
        });
    }
};
