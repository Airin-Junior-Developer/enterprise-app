<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $primaryKey = 'branch_id'; // ระบุ Primary Key ถูกต้องแล้ว

    protected $fillable = [
        'branch_name',
        'address' // ✅ แก้จาก description เป็น address ให้ตรงกับ Database Schema
    ];
}