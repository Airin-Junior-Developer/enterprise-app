<?php

use Illuminate\Support\Facades\Route;

// หน้าแรก (Root)
Route::get('/', function () {
    return view('welcome');
});

// หน้าอื่นๆ ทั้งหมด (Catch-all) ให้ Vue จัดการ
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');