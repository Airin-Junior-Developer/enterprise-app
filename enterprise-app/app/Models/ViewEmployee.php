<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewEmployee extends Model
{
    // 1. ชี้ไปที่ View ที่รวบรวมข้อมูลพนักงาน สาขา และตำแหน่งไว้แล้ว
    protected $table = 'employees_list';

    // 2. ระบุ Primary Key ของ View (ซึ่งดึงมาจาก u.user_id)
    protected $primaryKey = 'user_id';

    // 3. ปิด timestamps และการเพิ่มข้อมูล เพราะ View แก้ไขโดยตรงไม่ได้
    public $timestamps = false;

    // 4. (ทางเลือก) ป้องกันการเผลอไปสั่ง Save หรือ Create บน View นี้
    protected $guarded = ['*'];
}