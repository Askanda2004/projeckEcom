<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // เพิ่มคอลัมน์ slip_path หลัง method (เก็บ path รูปสลิปใน storage)
            if (!Schema::hasColumn('payments', 'slip_path')) {
                $table->string('slip_path')->nullable()->after('method');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'slip_path')) {
                $table->dropColumn('slip_path');
            }
        });
    }
};
