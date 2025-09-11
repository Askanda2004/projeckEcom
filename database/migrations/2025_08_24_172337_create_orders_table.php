<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            // ใช้ helper นี้จะสร้าง unsignedBigInteger + FK ไป users.id ให้อัตโนมัติ
            $table->unsignedBigInteger('user_id');
            // $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->dateTime('order_date');
            $table->enum('status', ['pending','paid','shipped','completed','canceled'])->default('pending');
            $table->decimal('total_amount',10,2);
            $table->unsignedBigInteger('payment_id')->nullable(); // ไม่ต้องทำ FK ข้ามไป payments ตอนนี้
            $table->timestamps();
        });
        // Schema::create('orders', function (Blueprint $table) {
        //     $table->id('order_id');                         // PK
        //     $table->unsignedBigInteger('user_id');          // FK -> users.user_id (หรือ users.id ถ้าคุณใช้ค่า default)
        //     $table->dateTime('order_date');                 // วันที่สั่งซื้อ
        //     $table->enum('status', [                        // สถานะหลักที่พอใช้งาน
        //         'pending', 'paid', 'shipped', 'completed', 'canceled'
        //     ])->default('pending');
        //     $table->decimal('total_amount', 10, 2);         // ราคารวมของออเดอร์

        //     // ถ้าต้องการเก็บ payment_id ตามสคีมาที่บอกไว้
        //     $table->unsignedBigInteger('payment_id')->nullable()->index(); // ยังไม่ทำ FK เพื่อตัดปัญหา FK วนกัน
        //     $table->timestamps();

        //     // FK users (ปรับคอลัมน์อ้างอิงตามจริงในตาราง users ของคุณ)
        //     $table->foreign('user_id')
        //           ->references('user_id')   // ถ้า users ใช้ 'id' ให้เปลี่ยนเป็น ->references('id')
        //           ->on('users')
        //           ->onDelete('cascade');
        // });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
