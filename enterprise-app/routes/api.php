<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hr\EmployeeController;
use App\Http\Controllers\Hr\BranchController;
use App\Http\Controllers\Hr\PositionController;
use App\Http\Controllers\Hr\RequestController;
use App\Http\Controllers\Hr\DashboardController;
use App\Http\Controllers\Hr\AuthController; // เพิ่มบรรทัดนี้

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. ประตูด่านหน้า (ไม่ต้องใช้บัตรผ่าน)
Route::post('/login', [AuthController::class, 'login']);

// 2. โซนปลอดภัย (ต้องมีบัตรผ่าน)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']); // ทางออก

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('branches', BranchController::class);
    Route::apiResource('positions', PositionController::class);
    Route::apiResource('requests', RequestController::class);

    Route::get('/dashboard/stats', [DashboardController::class, 'index']);
});
