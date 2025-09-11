<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void {
        Schema::table('order_items', function (Blueprint $table) {
            // 1) ตัด FK เดิม
            $table->dropForeign(['product_id']);
        });
        Schema::table('order_items', function (Blueprint $table) {
            // 2) ให้ product_id เป็น nullable
            $table->unsignedBigInteger('product_id')->nullable()->change();
            // 3) ผูก FK ใหม่ เป็น SET NULL
            $table->foreign('product_id')
                  ->references('product_id')->on('products')
                  ->nullOnDelete(); // or ->onDelete('set null') สำหรับ Laravel เก่า
        });
    }
    public function down(): void {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->foreign('product_id')
                  ->references('product_id')->on('products')
                  ->onDelete('restrict');
        });
    }
};
