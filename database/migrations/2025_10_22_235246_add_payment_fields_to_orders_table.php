<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            // ถ้าไม่มีคอลัมน์อยู่แล้วค่อยเพิ่ม (กันซ้ำ)
            if (!Schema::hasColumn('orders','payment_status')) {
                $t->enum('payment_status', ['unpaid','pending','verified','rejected'])
                  ->default('unpaid')->after('status');
            }
            if (!Schema::hasColumn('orders','payment_slip')) {
                $t->string('payment_slip')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders','payment_verified_by')) {
                $t->unsignedBigInteger('payment_verified_by')->nullable()->after('payment_slip');
            }
            if (!Schema::hasColumn('orders','payment_verified_at')) {
                $t->timestamp('payment_verified_at')->nullable()->after('payment_verified_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            if (Schema::hasColumn('orders','payment_verified_at')) $t->dropColumn('payment_verified_at');
            if (Schema::hasColumn('orders','payment_verified_by')) $t->dropColumn('payment_verified_by');
            if (Schema::hasColumn('orders','payment_slip'))        $t->dropColumn('payment_slip');
            if (Schema::hasColumn('orders','payment_status'))      $t->dropColumn('payment_status');
        });
    }
};
