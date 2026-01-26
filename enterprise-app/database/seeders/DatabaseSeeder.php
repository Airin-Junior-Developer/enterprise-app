<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. สร้าง User Admin (เอาไว้ Login)
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. สร้างสาขา (Branches)
        DB::table('branches')->insert([
            ['name' => 'สำนักงานใหญ่', 'address' => 'กรุงเทพมหานคร', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'สาขาเชียงใหม่', 'address' => 'อ.เมือง จ.เชียงใหม่', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'สาขาภูเก็ต', 'address' => 'อ.เมือง จ.ภูเก็ต', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. สร้างตำแหน่ง (Positions)
        DB::table('positions')->insert([
            ['name' => 'ผู้จัดการ (Manager)', 'description' => 'ดูแลภาพรวม', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'โปรแกรมเมอร์ (Developer)', 'description' => 'พัฒนาระบบ', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ฝ่ายบุคคล (HR)', 'description' => 'ดูแลพนักงาน', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. สร้างพนักงานตัวอย่าง (Employee)
        // ต้องดึง ID ของ User, Branch, Position มาใส่
        $userId = DB::table('users')->where('email', 'admin@example.com')->value('id');
        $branchId = DB::table('branches')->first()->id;
        $positionId = DB::table('positions')->first()->id;

        DB::table('employees')->insert([
            'user_id' => $userId,
            'prefix' => 'นาย',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'phone_number' => '0812345678',
            'branch_id' => $branchId,
            'position_id' => $positionId,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
