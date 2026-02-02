<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewEmployee extends Model
{
    // ชี้ไปที่ View ที่เราเพิ่งสร้าง
    protected $table = 'employees_list';

    // View เป็นแบบ "อ่านได้อย่างเดียว" ให้ปิด timestamps
    public $timestamps = false;
}