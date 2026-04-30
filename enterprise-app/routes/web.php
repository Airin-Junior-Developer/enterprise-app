<?php

use Illuminate\Support\Facades\Route;

// หน้าแรก (Root)
Route::get('/', function () {
    return view('welcome');
});

// หน้าสำหรับการกู้คืนฐานข้อมูลชั่วคราว
Route::get('/restore-db', function () {
    try {
        $sql = file_get_contents('/Users/airin/Downloads/enterprise_db.sql');
        \Illuminate\Support\Facades\DB::unprepared($sql);
        return 'กู้คืนฐานข้อมูล (Restore Database) สำเร็จแล้วครับ! 🎉 สามารถกลับไปหน้าแรกเพื่อใช้งานได้เลย';
    } catch (\Exception $e) {
        return 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    }
});

Route::get('/patch-db', function () {
    try {
        // เพิ่มคอลัมน์ที่ขาดหายไปในตาราง positions
        \Illuminate\Support\Facades\Schema::table('positions', function ($table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('positions', 'priority_level')) {
                $table->integer('priority_level')->default(99);
            }
        });

        // เพิ่มคอลัมน์ที่ขาดหายไปในตาราง users
        \Illuminate\Support\Facades\Schema::table('users', function ($table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'original_position_id')) {
                $table->unsignedBigInteger('original_position_id')->nullable();
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'temp_position_end_date')) {
                $table->date('temp_position_end_date')->nullable();
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'is_notify_expired')) {
                $table->boolean('is_notify_expired')->default(false);
            }
        });

        return 'อัปเดตโครงสร้างฐานข้อมูล (Patch DB) สำเร็จแล้วครับ! ข้อมูลไม่หายและพร้อมใช้งาน 🚀';
    } catch (\Exception $e) {
        return 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    }
});

// หน้าอื่นๆ ทั้งหมด (Catch-all) ให้ Vue จัดการ
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api|restore-db).*$');