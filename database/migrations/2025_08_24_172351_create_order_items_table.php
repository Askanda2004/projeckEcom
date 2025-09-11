<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');                    // PK
            $table->unsignedBigInteger('order_id');         // FK -> orders.order_id
            $table->unsignedBigInteger('product_id');       // FK -> products.product_id
            $table->integer('quantity');                    // จำนวนที่ซื้อ
            $table->decimal('price', 10, 2);                // ราคาต่อชิ้นตอนสั่งซื้อ
            $table->timestamps();

            // FK to orders
            $table->foreign('order_id')
                  ->references('order_id')->on('orders')
                  ->onDelete('cascade');

            // FK to products
            $table->foreign('product_id')
                  ->references('product_id')->on('products')
                  ->onDelete('restrict'); // หรือ cascade ถ้าต้องการให้ลบ item เมื่อสินค้าถูกลบ (ไม่ค่อยแนะนำ)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
