<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Hr\Branch;
use App\Models\Hr\Position;
use App\Models\Hr\Request as RequestModel; // ตั้งชื่อ alias เพื่อไม่ให้ชนกับ Request ของ Laravel

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ปิดการตรวจสอบ Foreign Key ชั่วคราว (เพื่อให้ลบข้อมูลได้)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. ล้างข้อมูลเก่าทิ้ง (เฉพาะตารางที่มีอยู่จริง)
        User::truncate();
        Branch::truncate();
        Position::truncate();
        RequestModel::truncate();

        // 3. เปิดการตรวจสอบ Foreign Key กลับคืน
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --- เริ่มสร้างข้อมูลจำลอง ---

        // 4. สร้างสาขา (Branches)
        $branch1 = Branch::create(['branch_name' => 'สำนักงานใหญ่ (Headquarters)', 'description' => 'ศูนย์กลางการบริหาร']);
        $branch2 = Branch::create(['branch_name' => 'สาขาเชียงใหม่', 'description' => 'ภาคเหนือ']);
        $branch3 = Branch::create(['branch_name' => 'สาขาภูเก็ต', 'description' => 'ภาคใต้']);

        // 5. สร้างตำแหน่ง (Positions)
        $posAdmin = Position::create(['position_name' => 'System Admin', 'description' => 'ผู้ดูแลระบบสูงสุด']);
        $posHr = Position::create(['position_name' => 'HR Manager', 'description' => 'ผู้จัดการฝ่ายบุคคล']);
        $posDev = Position::create(['position_name' => 'Developer', 'description' => 'นักพัฒนาซอฟต์แวร์']);
        $posSale = Position::create(['position_name' => 'Sales', 'description' => 'พนักงานขาย']);

        // 6. สร้าง User Admin (สำหรับ Login)
        User::create([
            'prefix' => 'นาย',
            'first_name' => 'Admin',
            'last_name' => 'System',
            'email' => 'admin@enterprise.com',
            'password' => Hash::make('12345678'), // รหัสผ่าน
            'phone_number' => '0812345678',
            'branch_id' => $branch1->branch_id,
            'position_id' => $posAdmin->position_id,
            'status' => 'active'
        ]);

        // 7. สร้าง User ทั่วไป (ตัวอย่าง)
        User::create([
            'prefix' => 'นางสาว',
            'first_name' => 'Jiraporn',
            'last_name' => 'Manager',
            'email' => 'hr@enterprise.com',
            'password' => Hash::make('12345678'),
            'phone_number' => '0899999999',
            'branch_id' => $branch1->branch_id,
            'position_id' => $posHr->position_id,
            'status' => 'active'
        ]);

        User::create([
            'prefix' => 'นาย',
            'first_name' => 'Somchai',
            'last_name' => 'Dev',
            'email' => 'dev@enterprise.com',
            'password' => Hash::make('12345678'),
            'phone_number' => '0888888888',
            'branch_id' => $branch2->branch_id,
            'position_id' => $posDev->position_id,
            'status' => 'active'
        ]);
    }
}
