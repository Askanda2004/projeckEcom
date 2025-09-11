<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // เปลี่ยน id เดิมเป็น user_id (ถ้าสร้างโปรเจกต์ใหม่ ให้ลบ/ปรับตามจริง)
            // กรณีมีคอลัมน์ id อยู่แล้ว ข้ามส่วนนี้ และใช้ id เดิมก็ได้
            // ---- ตัวอย่างกรณีใช้ id เดิม ----
            // $table->unsignedBigInteger('user_id')->virtualAs('id'); // ถ้าต้อง "แสดง" เป็น user_id
            // -----------------------------------

            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('user_id');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['customer','admin','seller'])
                      ->default('customer')
                      ->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
        });
    }
};