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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // เชื่อมโยงกับ User Login (1 คน มี 1 User)
            // onDelete('cascade') แปลว่า ถ้าลบ User ทิ้ง ให้ลบข้อมูลพนักงานคนนี้ทิ้งด้วย
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // ข้อมูลส่วนตัว
            $table->string('prefix', 50)->nullable()->comment('คำนำหน้าชื่อ');
            $table->string('first_name')->comment('ชื่อจริง');
            $table->string('last_name')->comment('นามสกุล');
            $table->string('phone_number', 20)->nullable()->comment('เบอร์โทรศัพท์');
            $table->string('id_card_number', 13)->nullable()->unique()->comment('เลขบัตร ปชช.');
            $table->date('birth_date')->nullable()->comment('วันเกิด');
            $table->text('address')->nullable()->comment('ที่อยู่ตามทะเบียนบ้าน');

            // ข้อมูลการทำงาน (เชื่อมโยงกับตารางอื่น)
            // set null แปลว่า ถ้าสาขาถูกลบ ให้ช่องนี้กลายเป็นว่าง (พนักงานไม่หาย)
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            $table->foreignId('position_id')->nullable()->constrained('positions')->onDelete('set null');

            // สถานะพนักงาน (Active, Resigned, Suspended)
            $table->enum('status', ['active', 'inactive', 'resigned'])->default('active');

            $table->timestamps(); // สร้าง created_at, updated_at อัตโนมัติ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
