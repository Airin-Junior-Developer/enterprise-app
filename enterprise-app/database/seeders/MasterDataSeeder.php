<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run()
    {
        // ข้อมูลประเภทการจ้าง
        DB::table('employment_types')->insert([
            ['name' => 'รายเดือน'],
            ['name' => 'รายวัน'],
            ['name' => 'รายชั่วโมง'], // เผื่ออนาคต
            ['name' => 'ตามสัญญาจ้าง'], // เผื่ออนาคต
        ]);

        // ข้อมูลประเภทพนักงาน
        DB::table('employee_categories')->insert([
            ['name' => 'ประจำ'],
            ['name' => 'ชั่วคราว'],
            ['name' => 'ทดลองงาน'], // เผื่ออนาคต
            ['name' => 'ฝึกงาน'], // เผื่ออนาคต
        ]);
    }
}