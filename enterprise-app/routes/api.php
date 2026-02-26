<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hr\AuthController;
use App\Http\Controllers\Hr\DashboardController;
use App\Http\Controllers\Hr\EmployeeController;
use App\Http\Controllers\Hr\BranchController;
use App\Http\Controllers\Hr\PositionController;
use App\Http\Controllers\Hr\RequestController;
use App\Http\Controllers\Hr\RequestTypeController;
use App\Http\Controllers\Hr\MasterDataController;

/*
|--------------------------------------------------------------------------
| API Routes - Modular Enterprise Management System
|--------------------------------------------------------------------------
*/

// 1. ประตูด่านหน้า (Public)
Route::post('/login', [AuthController::class, 'login']);

// 2. โซนสมาชิก (ต้อง Login ผ่าน Sanctum เท่านั้น)
Route::middleware('auth:sanctum')->group(function () {

    // ระบบ Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // ระบบ Dashboard (รองรับการดึง Stats และ Recent Requests)
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // ข้อมูลพื้นฐานสำหรับ Dropdown ทั่วไป
    Route::get('/branches', [BranchController::class, 'index']);
    Route::get('/positions', [PositionController::class, 'index']);
    Route::get('/request-types', [RequestController::class, 'getTypes']); // ดึงเฉพาะที่ Is_Active = 1

    // Master Data ใหม่ (สำหรับหน้า Employee Manager)
    Route::get('/employment-types', [MasterDataController::class, 'employmentTypes']);
    Route::get('/employee-categories', [MasterDataController::class, 'employeeCategories']);

    // รายชื่อพนักงาน (ดูได้อย่างเดียวสำหรับคนทั่วไป)
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::get('/employees/{id}', [EmployeeController::class, 'show']);

    // การจัดการคำร้องส่วนตัว (Employee Zone)
    Route::get('/requests', [RequestController::class, 'index']);
    Route::post('/requests', [RequestController::class, 'store']);
    Route::delete('/requests/{id}', [RequestController::class, 'destroy']);
    
    // ดึงข้อมูลสำหรับหน้าจัดการตำแหน่งงานจาก View
    Route::get('/manage-positions-list', [PositionController::class, 'getManagePositions']);

    // API สำหรับปิดสวิตช์แจ้งเตือนหมดเวลารักษาการ
    Route::post('/clear-expired-alert', function (Illuminate\Http\Request $request) {
        $user = $request->user();
        $user->is_notify_expired = 0; // ปิดสวิตช์
        $user->save();
        return response()->json(['message' => 'Cleared']);
    });
    
    // ---------------------------------------------------------
    // 3. โซนหวงห้าม (Admin & HR) - จัดการผ่าน Custom Middleware
    // ---------------------------------------------------------
    Route::middleware('admin_hr')->group(function () {

        Route::put('/requests/{id}/status', [RequestController::class, 'updateStatus']);

        // อนุมัติ/ปฏิเสธ คำร้อง (Approve/Reject)
        Route::put('/requests/{id}', [RequestController::class, 'update']);

        // จัดการพนักงาน (Full CRUD)
        Route::post('/employees', [EmployeeController::class, 'store']);
        Route::put('/employees/{id}', [EmployeeController::class, 'update']);
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
        Route::patch('/employees/{id}/position', [EmployeeController::class, 'updatePosition']);
        
        // จัดการสาขา (Full CRUD)
        Route::post('/branches', [BranchController::class, 'store']);
        Route::put('/branches/{id}', [BranchController::class, 'update']);
        Route::delete('/branches/{id}', [BranchController::class, 'destroy']);

        // จัดการตำแหน่ง (Full CRUD)
        Route::post('/positions', [PositionController::class, 'store']);
        Route::put('/positions/{id}', [PositionController::class, 'update']);
        Route::delete('/positions/{id}', [PositionController::class, 'destroy']);

        // จัดการประเภทคำร้อง (Full CRUD)
        Route::get('/request-types/all', [RequestTypeController::class, 'indexAll']);
        Route::post('/request-types', [RequestTypeController::class, 'store']);
        Route::put('/request-types/{id}', [RequestTypeController::class, 'update']);
        Route::patch('/request-types/{id}/toggle', [RequestTypeController::class, 'toggleStatus']);

        // ข้อมูลสรุป Master Data ทั้งหมด
        Route::get('/master-data', [MasterDataController::class, 'index']);
    });
});