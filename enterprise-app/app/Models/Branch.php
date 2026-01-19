<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    // ชื่อตารางในฐานข้อมูล
    protected $table = 'branches';

    // อนุญาตให้แก้ไขข้อมูลในคอลัมน์เหล่านี้
    protected $fillable = ['name', 'code', 'status'];
}