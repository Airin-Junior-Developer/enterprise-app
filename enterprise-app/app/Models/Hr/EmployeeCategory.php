<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeCategory extends Model
{
    use HasFactory;

    // 1. ระบุชื่อตาราง (Optional: ถ้าชื่อตรงตามมาตรฐาน Laravel อยู่แล้วไม่ต้องใส่ก็ได้ แต่ใส่ไว้ชัวร์กว่า)
    protected $table = 'employee_categories';

    // 2. อนุญาตให้บันทึกข้อมูลลงในคอลัมน์ 'name' ได้ (Mass Assignment)
    protected $fillable = ['name'];
}