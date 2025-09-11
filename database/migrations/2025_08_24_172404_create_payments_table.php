<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');                       // PK
            $table->unsignedBigInteger('order_id');         // FK -> orders.order_id
            $table->decimal('amount', 10, 2);               // จำนวนเงินที่จ่าย
            $table->dateTime('payment_date')->nullable();   // วันที่ชำระ
            $table->string('method', 50)->nullable();       // ช่องทาง เช่น transfer, card, qr
            $table->enum('status', ['pending','success','failed'])->default('pending');
            $table->timestamps();

            $table->foreign('order_id')
                  ->references('order_id')->on('orders')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
