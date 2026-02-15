<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    // 1. ระบุชื่อตาราง (เผื่อความชัวร์)
    protected $table = 'roles';

    // 2. ระบุชื่อ Primary Key ให้ตรงกับใน Database
    protected $primaryKey = 'role_id';

    // 3. กำหนดฟิลด์ที่อนุญาตให้แก้ไขได้ (Mass Assignment)
    protected $fillable = [
        'role_name',
        'description',
        'permissions' // เก็บ JSON สิทธิ์รายโมดูล
    ];

    // 4. แปลงข้อมูล JSON ใน Database ให้เป็น Array ใน PHP อัตโนมัติ
    // เวลาเรียก $role->permissions จะได้ Array ทันที ไม่ต้อง json_decode เอง
    protected $casts = [
        "employee_management": ["create", "read", "update"],
  "payroll": ["read"]
    ];

    // ความสัมพันธ์กับ User (Many-to-Many)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }
}
