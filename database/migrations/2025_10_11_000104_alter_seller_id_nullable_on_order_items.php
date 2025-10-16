<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ถ้ามีคอลัมน์อยู่แล้ว ให้แก้ไขเป็น nullable
        if (Schema::hasColumn('order_items', 'seller_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->unsignedBigInteger('seller_id')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('order_items', 'seller_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->unsignedBigInteger('seller_id')->nullable(false)->change();
            });
        }
    }
};

