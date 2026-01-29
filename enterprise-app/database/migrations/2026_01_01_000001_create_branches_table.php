<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            // ตั้งชื่อ Primary Key ว่า branch_id ให้ชัดเจน
            $table->id('branch_id');

            // ✅ เปลี่ยนจาก name เป็น branch_name
            $table->string('branch_name');

            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
