<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); // <-- สั่งให้เปิดหน้า welcome.blade.php ที่เราเพิ่งใส่ Vue ลงไป
});