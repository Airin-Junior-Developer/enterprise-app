<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hr\PersonnelController; // <--- ต้องมีบรรทัดนี้

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// เส้นทางดึงพนักงาน
Route::get('/employees', [PersonnelController::class, 'index']);
Route::post('/employees', [PersonnelController::class, 'store']);