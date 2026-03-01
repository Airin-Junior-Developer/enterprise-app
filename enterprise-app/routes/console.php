<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * คำสั่งสร้าง Super Admin ผ่าน Terminal
 * วิธีใช้: php artisan make:admin {email} {password}
 */
Artisan::command('make:admin {email} {password}', function ($email, $password) {
    $user = User::create([
        'first_name' => 'System',
        'last_name' => 'Administrator',
        'email' => $email,
        'password' => Hash::make($password),
        // สมมติว่า ID 1 คือตำแหน่ง Super Admin และ ID 1 คือสาขาสำนักงานใหญ่
        'position_id' => 1,
        'branch_id' => 1,
        'employment_type_id' => 1,
        'employee_category_id' => 1,
    ]);

    $this->info("Successfully created Super Admin: {$email}");
})->purpose('Create a new Super Admin user');

/**
 * คำสั่งตรวจสอบสถานะ API และ Database เบื้องต้น
 */
Artisan::command('hr:check', function () {
    $this->info('--- HR System Status Check ---');

    $users = User::count();
    $this->line("Total Employees: {$users}");

    try {
        \DB::connection()->getPdo();
        $this->info('Database Connection: OK');
    } catch (\Exception $e) {
        $this->error('Database Connection: FAILED');
    }
})->purpose('Check system health status');

// รันคำสั่งนี้ทุกๆ เที่ยงคืน (00:00 น.) ของทุกวัน
Schedule::call(function () {
    
    // ค้นหาพนักงานที่ตำแหน่งชั่วคราวหมดอายุแล้ว (วันที่สิ้นสุด น้อยกว่า วันนี้)
    DB::table('users')
        ->whereNotNull('temp_position_end_date')
        ->whereDate('temp_position_end_date', '<', now())
        ->update([
            // เอา position_id เดิมที่จำไว้ กลับมาใส่ทับตำแหน่งปัจจุบัน
            'position_id' => DB::raw('original_position_id'),
            // ล้างค่าชั่วคราวทิ้ง
            'original_position_id' => null,
            'temp_position_end_date' => null,
            'is_notify_expired' => 1 // ✅ เปิดสวิตช์ให้เด้งแจ้งเตือนตอนล็อกอิน
            ]);

})->daily();