<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_position_histories', function (Blueprint $table) {
            Schema::create('employee_position_histories', function (Blueprint $table) {
    $table->id();

    // พนักงาน
    $table->foreignId('user_id')
      ->constrained()
      ->cascadeOnDelete();

    // ตำแหน่งที่ถูกปรับ
    $table->foreignId('position_id')->constrained()->cascadeOnDelete();

    // ถาวร / ชั่วคราว
    $table->enum('type',['permanent','temporary']);

    // วันที่เริ่ม
    $table->date('start_date')->nullable();

    // วันที่หมดอายุ
    $table->date('end_date')->nullable();

    $table->timestamps();
});

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_position_histories');
    }
};
