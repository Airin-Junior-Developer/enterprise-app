<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'positions';
    protected $primaryKey = 'position_id';

    // ✅ เพิ่มคอลัมน์ใหม่ลงใน fillable
    protected $fillable = [
        'position_name',
        'position_name_en',
        'level_code',
        'description',
        'employment_type_id',
        'employee_category_id',
        'min_salary',
        'max_salary',
        'is_active'
    ];

    // ✅ เชื่อมความสัมพันธ์กับประเภทการจ้าง
    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class, 'employment_type_id', 'id');
    }

    // ✅ เชื่อมความสัมพันธ์กับหมวดหมู่พนักงาน
    public function employeeCategory()
    {
        return $this->belongsTo(EmployeeCategory::class, 'employee_category_id', 'id');
    }
}