<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Hr\Branch;
use App\Models\Hr\Position;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // ระบุ Primary Key ให้ตรงกับ Migration
    protected $primaryKey = 'user_id';

    // เพิ่มรายชื่อฟิลด์ที่อนุญาตให้บันทึก (ต้องตรงกับ Database)
    protected $fillable = [
        'prefix',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'id_card_number',
        'branch_id',
        'position_id',
        'employment_type_id',   // ✅ ต้องมีบรรทัดนี้
        'employee_category_id', // ✅ ต้องมีบรรทัดนี้
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ความสัมพันธ์ (Relationships)
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    // ในไฟล์ App\Models\User.php

    public function position()
    {
        // เชื่อมไปยัง Model Position โดยใช้ position_id
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }

    // เพิ่มฟังก์ชันเช็คสิทธิ์
    public function isAdminOrHr()
    {
        // โหลดความสัมพันธ์ถ้ายังไม่มี
        if (!$this->relationLoaded('position')) {
            $this->load('position');
        }

        // ป้องกัน Error กรณี position เป็น NULL
        if (!$this->position) {
            return false;
        }

        $posName = $this->position->position_name;

        // ตรวจสอบชื่อตำแหน่งให้ตรงกับที่มีใน Database จริงๆ
        return in_array($posName, ['Super Admin', 'HR Manager', 'System Admin']);
    }
    public function roles()
    {
        // เชื่อมไปยังตาราง user_roles ที่เราสร้างไว้เพื่อจับคู่ User กับ Role
        return $this->belongsToMany(\App\Models\Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function employmentType()
    {
        return $this->belongsTo(\App\Models\Hr\EmploymentType::class, 'employment_type_id');
    }

    public function employeeCategory()
    {
        return $this->belongsTo(\App\Models\Hr\EmployeeCategory::class, 'employee_category_id');
    }
}
