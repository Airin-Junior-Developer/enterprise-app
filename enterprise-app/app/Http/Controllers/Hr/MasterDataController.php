<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\EmploymentType;
use App\Models\Hr\EmployeeCategory;

class MasterDataController extends Controller
{
    /**
     * สำหรับ Route: Route::get('/master-data', [MasterDataController::class, 'index']);
     * ใช้ในหน้า EmployeeManager ตอนดึงรวดเดียว
     */
    public function index()
    {
        try {
            return response()->json([
                'employment_types' => EmploymentType::all(),
                'employee_categories' => EmployeeCategory::all()
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * สำหรับ Route: Route::get('/employment-types', [MasterDataController::class, 'employmentTypes']);
     * แก้ปัญหา Error 500 เพราะหาฟังก์ชันไม่เจอ
     */
    public function employmentTypes()
    {
        try {
            return response()->json(EmploymentType::all());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * สำหรับ Route: Route::get('/employee-categories', [MasterDataController::class, 'employeeCategories']);
     * แก้ปัญหา Error 500 เพราะหาฟังก์ชันไม่เจอ
     */
    public function employeeCategories()
    {
        try {
            return response()->json(EmployeeCategory::all());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}