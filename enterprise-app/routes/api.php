<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hr\PersonnelController;
use App\Http\Controllers\Hr\BranchController;
use App\Http\Controllers\Hr\PositionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// เส้นทางดึงพนักงาน
Route::get('/employees', [PersonnelController::class, 'index']);
Route::post('/employees', [PersonnelController::class, 'store']);

// แก้ไขข้อมูล (PUT) และ ลบข้อมูล (DELETE)
Route::put('/employees/{id}', [PersonnelController::class, 'update']);
Route::delete('/employees/{id}', [PersonnelController::class, 'destroy']);

// ส่วนจัดการสาขา (Branch)
Route::get('/branches', [BranchController::class, 'index']);      // ดึงข้อมูล
Route::post('/branches', [BranchController::class, 'store']);     // เพิ่มใหม่
Route::put('/branches/{id}', [BranchController::class, 'update']); // แก้ไข
Route::delete('/branches/{id}', [BranchController::class, 'destroy']); // ลบ

// API จัดการตำแหน่งงาน
Route::get('/positions', [PositionController::class, 'index']);
Route::post('/positions', [PositionController::class, 'store']);     // เส้นทางสำหรับบันทึก
Route::put('/positions/{id}', [PositionController::class, 'update']);
Route::delete('/positions/{id}', [PositionController::class, 'destroy']);
