<?php

namespace App\Models\Hr; // 1. เช็ค namespace ว่าต้องเป็น App\Models\Hr

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';

    // 2. 🔥 บรรทัดนี้สำคัญที่สุด! ถ้าไม่มีจะบันทึกไม่ได้
    protected $fillable = ['name'];
}
