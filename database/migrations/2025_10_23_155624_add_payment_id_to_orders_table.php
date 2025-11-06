<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_id')) {
                $table->unsignedBigInteger('payment_id')->nullable()->after('order_date');
            }

            // ตรวจว่ามี foreign key หรือยัง ถ้ายังไม่มีค่อยเพิ่ม
            $table->foreign('payment_id')
                  ->references('payment_id')
                  ->on('payments')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // ลบ foreign key ก่อน
            $table->dropForeign(['payment_id']);
            // ลบคอลัมน์ด้วย
            $table->dropColumn('payment_id');
        });
    }
};
