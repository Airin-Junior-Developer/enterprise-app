<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    // 1. ระบุชื่อตารางที่มีอยู่แล้วใน Navicat (สมมติว่าชื่อ requests_type)
    protected $table = 'requests_type';

    public $timestamps = false;

    // 2. ถ้ารหัส PK ในตารางไม่ได้ชื่อ 'id' ให้แก้ตรงนี้ (เช่น 'request_type_id')
    // protected $primaryKey = 'id'; 

    // 3. ปิด timestamps ถ้ายในตารางคุณไม่มีคอลัมน์ created_at, updated_at
    // public $timestamps = false; 

    // 4. ระบุคอลัมน์ที่อนุญาตให้บันทึกข้อมูลได้
    protected $fillable = [
        'Name_Type',
        'is_active',
    ];
}