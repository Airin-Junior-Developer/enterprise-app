<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id('request_id'); // Primary Key

            // เชื่อมกับตาราง Users
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            // ข้อมูลคำร้อง
            $table->string('request_type'); // ประเภท (เช่น ลาป่วย, ปรับเงินเดือน)
            $table->text('reason')->nullable(); // เหตุผล / รายละเอียด
            $table->string('status')->default('pending'); // สถานะ (pending, approved, rejected)

            // ฟิลด์เผื่อเลือก (สำหรับวันลา หรือ จำนวนเงิน)
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('amount', 10, 2)->nullable(); // เผื่อปรับเงินเดือน

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
