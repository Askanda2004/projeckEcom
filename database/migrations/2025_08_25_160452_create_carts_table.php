<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('carts', function (Blueprint $table) {
            $table->id('cart_id');
            $table->unsignedBigInteger('user_id')->unique(); // 1 user มี 1 cart
            $table->timestamps();

            // ถ้า users ใช้ PK = id ให้เปลี่ยน references เป็น 'id'
            $table->foreign('user_id')
                  ->references('user_id')->on('users')
                  ->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('carts');
    }
};
