<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('seller_profiles', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('user_id')->unique();      // FK -> users.user_id
            $t->string('shop_name');
            $t->text('address')->nullable();
            $t->string('logo_path')->nullable();              // โลโก้
            $t->string('photo_path')->nullable();             // รูปร้าน/แบนเนอร์
            $t->timestamps();

            $t->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down(): void {
        Schema::dropIfExists('seller_profiles');
    }
};