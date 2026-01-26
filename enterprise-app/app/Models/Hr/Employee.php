<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    // แก้ชื่อคอลัมน์ให้ตรงกับ Database ใหม่ (first_name, last_name, etc.)
    protected $fillable = [
        'user_id',
        'prefix',
        'first_name',      // แก้จาก firstname
        'last_name',       // แก้จาก lastname
        'phone_number',    // แก้จาก phone
        'id_card_number',  // เพิ่ม
        'birth_date',      // เพิ่ม
        'address',         // เพิ่ม
        'branch_id',       // ต้องมีเพื่อเชื่อมสาขา
        'position_id',     // ต้องมีเพื่อเชื่อมตำแหน่ง (แก้จาก position ที่เป็น string)
        'status'
    ];

    // เชื่อมกับตาราง Users
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // เชื่อมกับตาราง Branches
    public function branch()
    {
        return $this->belongsTo(\App\Models\Hr\Branch::class);
    }

    // เชื่อมกับตาราง Positions
    public function position()
    {
        return $this->belongsTo(\App\Models\Hr\Position::class);
    }
}
