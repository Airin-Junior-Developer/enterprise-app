<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    // ระบุชื่อตาราง (เผื่อไว้)
    protected $table = 'positions';

    // ✅ ระบุ Primary Key (สำคัญมาก เพราะไม่ได้ใช้ 'id')
    protected $primaryKey = 'position_id';

    // ✅ เพิ่มรายชื่อฟิลด์ทั้งหมดลงใน fillable
    protected $fillable = [
        'position_name',
        'position_name_en',
        'level_code',
        'priority_level',
        'employment_type_id',
        'employee_category_id',
        'description',
        'min_salary',
        'max_salary',
        'is_active'
    ];

    // ความสัมพันธ์กับ User (เพื่อเช็คก่อนลบ)
    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'position_id', 'position_id');
    }
}
