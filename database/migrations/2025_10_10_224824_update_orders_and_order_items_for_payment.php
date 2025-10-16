<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method', 50)->default('transfer')->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'payment_slip')) {
                $table->string('payment_slip', 255)->nullable()->after('payment_method');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'seller_id')) {
                $table->unsignedBigInteger('seller_id')->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('order_items', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_slip']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['seller_id', 'subtotal']);
        });
    }
};
