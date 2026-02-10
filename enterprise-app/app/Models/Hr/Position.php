<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $primaryKey = 'position_id'; // ✅ ต้องมีบรรทัดนี้ เพราะ PK เราไม่ใช่ 'id'

    protected $fillable = [
        'position_name',
        'position_name_en',
        'level',
        'level_code',
        'employment_type_id',
        'employee_category_id',
        'min_salary',
        'max_salary',
        'is_active',
    ];

    // ✅ ต้องมี 2 ฟังก์ชันนี้ เพื่อแก้ Error 500 (0 รายการ)
    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class, 'employment_type_id');
    }

    public function employeeCategory()
    {
        return $this->belongsTo(EmployeeCategory::class, 'employee_category_id');
    }
}