<?php

namespace App\Models; // ตรวจสอบว่า Namespace ตรงกับโฟลเดอร์ Models

use Illuminate\Database\Eloquent\Model;

class ManagePositionView extends Model
{
    // ระบุชื่อ View ให้ตรงกับที่สร้างใน Navicat
    protected $table = 'view_manage_positions'; 
    
    // ตั้งค่าเนื่องจาก View ไม่มี PK และ Timestamps
    public $incrementing = false;
    public $timestamps = false;
}