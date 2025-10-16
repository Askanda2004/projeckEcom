<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // เพิ่มคอลัมน์สลิป (path ใน storage) ถ้ายังไม่มี
            if (!Schema::hasColumn('orders', 'payment_slip')) {
                $table->string('payment_slip')->nullable()->after('status');
            }

            // เพิ่มสถานะการชำระเงิน ถ้ายังไม่มี
            // หมายเหตุ: ถ้าใช้ MySQL แนะนำ enum ได้ตามนี้
            // ถ้าใช้ SQLite ให้เปลี่ยนเป็น string(20) แล้ว validate ในแอปแทน
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $driver = Schema::getConnection()->getDriverName();
                if ($driver === 'sqlite') {
                    $table->string('payment_status', 20)->default('pending')->after('payment_slip');
                } else {
                    $table->enum('payment_status', ['pending','verified','rejected'])->default('pending')->after('payment_slip');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // ใช้ dropColumn แบบมีเงื่อนไข ป้องกัน error เวลา rollback หลายรอบ
            if (Schema::hasColumn('orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('orders', 'payment_slip')) {
                $table->dropColumn('payment_slip');
            }
        });
    }
};
