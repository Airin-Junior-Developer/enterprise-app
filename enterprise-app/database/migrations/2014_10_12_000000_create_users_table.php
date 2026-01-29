<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // ใช้ user_id เป็น Primary Key ตามที่ Frontend เรียกใช้
            $table->id('user_id');

            // --- ข้อมูลส่วนตัว (Section 1) ---
            $table->string('prefix')->nullable();          // คำนำหน้า (นาย, นาง, ฯลฯ)
            $table->string('first_name');                  // ชื่อจริง
            $table->string('last_name');                   // นามสกุล
            $table->string('id_card_number', 13)->nullable(); // เลขบัตร ปชช. (13 หลัก)
            $table->string('phone_number')->nullable();    // เบอร์โทร

            // --- ข้อมูลล็อกอิน (Section 3) ---
            $table->string('email')->unique();             // อีเมล (ห้ามซ้ำ)
            $table->string('password');                    // รหัสผ่าน

            // --- สังกัด (Section 2) ---
            // เชื่อมกับตาราง branches และ positions
            // (ต้องมีตาราง branches และ positions ก่อน ถึงจะรันผ่าน)
            $table->foreignId('branch_id')->nullable();
            $table->foreignId('position_id')->nullable();

            // --- สถานะ ---
            $table->string('status')->default('active');   // สถานะ (active, inactive)

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
