<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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