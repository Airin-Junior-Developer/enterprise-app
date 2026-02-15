<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewRequest extends Model
{
    // 1. ชี้ไปที่ View ที่รวมข้อมูลคำร้อง ประเภทคำร้อง และชื่อพนักงานไว้แล้ว
    protected $table = 'requests_list';

    // 2. ระบุ Primary Key ให้ Laravel รู้จัก (ดึงมาจาก r.request_id)
    protected $primaryKey = 'request_id';

    // 3. ปิด Timestamps เนื่องจาก View เป็นแบบอ่านอย่างเดียว (Read-only)
    public $timestamps = false;

    // 4. (ทางเลือก) ป้องกันการบันทึกข้อมูลทับโดยไม่ตั้งใจ
    protected $guarded = ['*'];
}