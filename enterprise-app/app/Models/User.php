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
    // เพิ่มฟังก์ชันเช็คสิทธิ์ (ฉบับปรับปรุง: ตัดช่องว่าง + ไม่สนตัวพิมพ์เล็กใหญ่)
    public function isAdminOrHr()
    {
        // ถ้าไม่มีตำแหน่ง ให้ดีดออกทันที
        if (!$this->position) {
            return false;
        }

        // 1. แปลงชื่อตำแหน่งใน DB ให้เป็นตัวพิมพ์เล็กทั้งหมด และตัดช่องว่างหน้าหลัง
        // เช่น " Super Admin " -> "super admin"
        $posName = strtolower(trim($this->position->position_name));

        // 2. รายชื่อตำแหน่งที่อนุญาต (เขียนตัวพิมพ์เล็กให้หมด)
        $allowedRoles = [
            'super admin',
            'hr manager',
            'system admin',
            'manager',  // เผื่อตำแหน่ง Manager ทั่วไป
            'admin'     // เผื่อตำแหน่ง Admin ทั่วไป
        ];

        // 3. ตรวจสอบว่ามีอยู่ในลิสต์ไหม
        return in_array($posName, $allowedRoles);
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
