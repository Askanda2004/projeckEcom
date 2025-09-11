<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_item_id');
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);         // snapshot ราคา ณ ตอนหยิบใส่ตะกร้า
            $table->string('size', 50)->nullable();  // เผื่อใช้
            $table->string('color', 50)->nullable(); // เผื่อใช้
            $table->string('image_url')->nullable(); // snapshot ภาพ
            $table->timestamps();

            $table->foreign('cart_id')
                  ->references('cart_id')->on('carts')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('product_id')->on('products')
                  ->onDelete('restrict');

            $table->unique(['cart_id','product_id']); // 1 สินค้า 1 แถว/ตะกร้า
        });
    }
    public function down(): void {
        Schema::dropIfExists('cart_items');
    }
};
