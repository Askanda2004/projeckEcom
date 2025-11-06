<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $dbName = config('database.connections.mysql.database');

        // 1) หาและลบ FOREIGN KEY ที่ผูกกับ payments.order_id (ถ้ามี)
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME   = 'payments'
              AND COLUMN_NAME  = 'order_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ", [$dbName]);

        if ($fk && !empty($fk->CONSTRAINT_NAME)) {
            DB::statement("ALTER TABLE `payments` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // 2) ลบ INDEX ใด ๆ บน order_id (ยกเว้น PRIMARY) ถ้ามี
        $indexes = DB::select("SHOW INDEX FROM `payments` WHERE Column_name = 'order_id'");
        foreach ($indexes as $idx) {
            if (!empty($idx->Key_name) && $idx->Key_name !== 'PRIMARY') {
                DB::statement("ALTER TABLE `payments` DROP INDEX `{$idx->Key_name}`");
            }
        }

        // 3) แก้คอลัมน์หรือเพิ่มคอลัมน์ที่ต้องการ
        // หมายเหตุ: ถ้าเครื่องคุณยังไม่ได้ติดตั้ง doctrine/dbal และเจอ error ตอน change()
        // ให้รัน: composer require doctrine/dbal  หรือใช้ DB::statement แทนด้านล่าง
        try {
            Schema::table('payments', function (Blueprint $table) {
                // ให้ order_id เป็น nullable เพื่อไม่บังคับต้องชี้หาใบสั่งซื้อเดียว
                if (Schema::hasColumn('payments', 'order_id')) {
                    $table->unsignedBigInteger('order_id')->nullable()->change();
                }

                // ฟิลด์กลุ่มการชำระเงิน (ตามที่ออกแบบเพื่อรวมสลิปเดียว)
                if (!Schema::hasColumn('payments', 'payment_group_id')) {
                    $table->unsignedBigInteger('payment_group_id')->nullable()->after('payment_id');
                }
                if (!Schema::hasColumn('payments', 'group_ref')) {
                    $table->string('group_ref', 36)->nullable()->after('payment_group_id');
                }
                if (!Schema::hasColumn('payments', 'payment_slip')) {
                    $table->string('payment_slip', 255)->nullable()->after('method');
                }
            });
        } catch (\Throwable $e) {
            // fallback ถ้า change() ใช้ไม่ได้บน MySQL เวอร์ชันคุณ
            DB::statement("ALTER TABLE `payments` MODIFY `order_id` BIGINT UNSIGNED NULL");
            if (!Schema::hasColumn('payments', 'payment_group_id')) {
                DB::statement("ALTER TABLE `payments` ADD `payment_group_id` BIGINT UNSIGNED NULL AFTER `payment_id`");
            }
            if (!Schema::hasColumn('payments', 'group_ref')) {
                DB::statement("ALTER TABLE `payments` ADD `group_ref` VARCHAR(36) NULL AFTER `payment_group_id`");
            }
            if (!Schema::hasColumn('payments', 'payment_slip')) {
                DB::statement("ALTER TABLE `payments` ADD `payment_slip` VARCHAR(255) NULL AFTER `method`");
            }
        }
    }

    public function down(): void
    {
        // ย้อนกลับแบบปลอดภัย (ลบคอลัมน์ที่เราเพิ่ม)
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'payment_slip')) {
                $table->dropColumn('payment_slip');
            }
            if (Schema::hasColumn('payments', 'group_ref')) {
                $table->dropColumn('group_ref');
            }
            if (Schema::hasColumn('payments', 'payment_group_id')) {
                $table->dropColumn('payment_group_id');
            }
        });

        // ไม่พยายามผูก FK กลับ (เพราะเราออกแบบให้ชำระรวมหลายออเดอร์แล้ว)
    }
};
