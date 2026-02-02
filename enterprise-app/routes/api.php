<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hr\EmployeeController;
use App\Http\Controllers\Hr\BranchController;
use App\Http\Controllers\Hr\PositionController;
use App\Http\Controllers\Hr\RequestController;
use App\Http\Controllers\Hr\DashboardController;
use App\Http\Controllers\Hr\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. ประตูด่านหน้า (Public)
Route::post('/login', [AuthController::class, 'login']);

// 2. โซนสมาชิก (Login แล้วเข้าได้ทุกคน)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Dashboard: ให้ทุกคนดูได้ (หรือจะย้ายไป Admin ก็ได้แล้วแต่คุณ)
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // --- 🟢 โซนทั่วไป (ทุกคนเข้าได้) ---

    // ดูรายชื่อเพื่อนร่วมงาน, สาขา, ตำแหน่ง (เอาไว้โชว์ใน Dropdown) แต่ห้ามแก้ไข
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::get('/employees/{id}', [EmployeeController::class, 'show']);

    Route::get('/branches', [BranchController::class, 'index']);
    Route::get('/branches/{id}', [BranchController::class, 'show']);

    Route::get('/positions', [PositionController::class, 'index']);
    Route::get('/positions/{id}', [PositionController::class, 'show']);

    // จัดการคำร้องของตัวเอง (ดู, สร้าง, ยกเลิก)
    Route::get('/requests', [RequestController::class, 'index']);
    Route::post('/requests', [RequestController::class, 'store']);
    Route::delete('/requests/{id}', [RequestController::class, 'destroy']);


    // --- 🔴 โซนหวงห้าม (เฉพาะ Admin และ HR) ---
    Route::middleware('admin_hr')->group(function () {

        // อนุมัติคำร้อง (Approve/Reject)
        Route::put('/requests/{id}', [RequestController::class, 'update']);

        // จัดการพนักงาน (เพิ่ม/ลบ/แก้ไข)
        Route::post('/employees', [EmployeeController::class, 'store']);
        Route::put('/employees/{id}', [EmployeeController::class, 'update']);
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);

        // จัดการสาขา (เพิ่ม/ลบ/แก้ไข)
        Route::post('/branches', [BranchController::class, 'store']);
        Route::put('/branches/{id}', [BranchController::class, 'update']);
        Route::delete('/branches/{id}', [BranchController::class, 'destroy']);

        // จัดการตำแหน่ง (เพิ่ม/ลบ/แก้ไข)
        Route::post('/positions', [PositionController::class, 'store']);
        Route::put('/positions/{id}', [PositionController::class, 'update']);
        Route::delete('/positions/{id}', [PositionController::class, 'destroy']);
    });

});