<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\EmploymentType;
use App\Models\Hr\EmployeeCategory;

class MasterDataController extends Controller
{
    // ดึงข้อมูลสำหรับ Dropdown ทั้งหมดในครั้งเดียว
    public function index()
    {
        return response()->json([
            // ดึงข้อมูลประเภทการจ้าง (รายเดือน, รายวัน)
            'employment_types' => EmploymentType::all(),

            // ดึงข้อมูลประเภทพนักงาน (ประจำ, ชั่วคราว)
            'employee_categories' => EmployeeCategory::all(),
        ]);
    }
}