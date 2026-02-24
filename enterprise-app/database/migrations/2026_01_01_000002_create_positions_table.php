<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('positions', function (Blueprint $table) {
            // เพิ่มคอลัมน์ priority_level ค่าเริ่มต้นให้เป็น 99 (ความสำคัญต่ำสุด)
            $table->integer('priority_level')->default(99)->after('level_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
