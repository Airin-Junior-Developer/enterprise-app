<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    // ระบุชื่อตารางใน Database (ปกติ Laravel จะเดาว่า branches แต่ใส่กันเหนียวไว้ครับ)
    protected $table = 'branches';

    // 👇 จุดสำคัญคือตรงนี้ครับ ต้องมี 'address' ด้วย
    protected $fillable = [
        'name',
        'address',
    ];

    // อนุญาตให้แก้ไขข้อมูล 2 ช่องนี้ได้
    // protected $fillable = ['name', 'code'];
}
