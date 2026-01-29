<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            // ตั้งชื่อ Primary Key ว่า position_id ให้ชัดเจน
            $table->id('position_id');

            // ✅ เปลี่ยนจาก name เป็น position_name
            $table->string('position_name');

            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
