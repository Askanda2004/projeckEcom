<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('path');           // เก็บ path ใน storage (เช่น storage/products/xxx.jpg)
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('ordering')->default(0);
            $table->timestamps();

            $table->foreign('product_id')
                  ->references('product_id')->on('products')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_images');
    }
};