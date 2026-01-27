<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';

    // 👇 ต้องเพิ่ม 'description' เข้าไปตรงนี้ครับ
    protected $fillable = [
        'name',
        'description'
    ];

    // ความสัมพันธ์กับพนักงาน
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}