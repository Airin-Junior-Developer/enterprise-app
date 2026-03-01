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
use App\Http\Controllers\Hr\ApprovalController;


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

    // ระบบ Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // --- Master Data (ดึงข้อมูลสำหรับ Dropdown) ---
    // (ให้สิทธิ์ทุกคนเห็นได้ เพื่อใช้ในฟอร์มต่างๆ)
    Route::get('/branches', [BranchController::class, 'index']);
    Route::get('/positions', [PositionController::class, 'index']);
    Route::get('/request-types', [RequestController::class, 'getTypes']);
    Route::get('/employment-types', [MasterDataController::class, 'employmentTypes']);
    Route::get('/employee-categories', [MasterDataController::class, 'employeeCategories']);

    // --- Employee List (Read Only) ---
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::get('/employees/{id}', [EmployeeController::class, 'show']);

    // --- ระบบคำร้องส่วนตัว (My Requests) ---
    // User ทั่วไป: ดู/สร้าง/ลบ คำร้องของตัวเอง
    Route::get('/requests', [RequestController::class, 'index']);
    Route::post('/requests', [RequestController::class, 'store']);
    Route::delete('/requests/{id}', [RequestController::class, 'destroy']);
    
    // ดึงข้อมูลสำหรับหน้าจัดการตำแหน่งงานจาก View
    Route::get('/manage-positions-list', [PositionController::class, 'getManagePositions']);

<<<<<<< HEAD
    // --- ระบบพิจารณาอนุมัติ (Approvals) ---
    // หัวหน้า/HR: ดูรายการที่ต้องอนุมัติ และกดเปลี่ยนสถานะ
    // *เปลี่ยน path เป็น /approvals เพื่อไม่ให้ชนกับ /requests ด้านบน*
    Route::get('/approvals', [ApprovalController::class, 'index']);
    Route::put('/approvals/{id}/status', [ApprovalController::class, 'updateStatus']);


=======
    // API สำหรับปิดสวิตช์แจ้งเตือนหมดเวลารักษาการ
    Route::post('/clear-expired-alert', function (Illuminate\Http\Request $request) {
        $user = $request->user();
        $user->is_notify_expired = 0; // ปิดสวิตช์
        $user->save();
        return response()->json(['message' => 'Cleared']);
    });
    
>>>>>>> origin/sea
    // ---------------------------------------------------------
    // 3. โซนหวงห้าม (Admin & HR Only) - แก้ไขข้อมูลหลัก
    // ---------------------------------------------------------
    Route::middleware('admin_hr')->group(function () {

        // จัดการพนักงาน (Create / Update / Delete)
        Route::post('/employees', [EmployeeController::class, 'store']);
        Route::put('/employees/{id}', [EmployeeController::class, 'update']);
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
<<<<<<< HEAD

        // จัดการสาขา (Create / Update / Delete)
=======
        Route::patch('/employees/{id}/position', [EmployeeController::class, 'updatePosition']);
        
        // จัดการสาขา (Full CRUD)
>>>>>>> origin/sea
        Route::post('/branches', [BranchController::class, 'store']);
        Route::put('/branches/{id}', [BranchController::class, 'update']);
        Route::delete('/branches/{id}', [BranchController::class, 'destroy']);

        // จัดการตำแหน่ง (Create / Update / Delete)
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
