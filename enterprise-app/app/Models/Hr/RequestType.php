<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    // 1. ระบุชื่อตารางให้ตรงกับใน Navicat
    protected $table = 'requests_type';

    // 2. เปิดใช้งาน Timestamps (ลบ public $timestamps = false ออก)
    // เพราะเราเพิ่มคอลัมน์ created_at, updated_at ใน DB ไปแล้ว
    public $timestamps = true;

    // 3. ระบุคอลัมน์ที่อนุญาตให้บันทึกข้อมูล (Mass Assignment)
    protected $fillable = [
        'Name_Type',
        'is_active',
    ];
}