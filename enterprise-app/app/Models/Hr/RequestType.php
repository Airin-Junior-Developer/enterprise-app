<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class RequestType extends Model
{
    use LogsActivity;

    protected static string $auditModule = 'HR';
    // ระบุชื่อตารางให้ตรงกับ Database
    protected $table = 'requests_type';

    // ระบุฟิลด์ที่อนุญาตให้แก้ไข
    protected $fillable = [
        'Name_Type', // ตรงกับ Vue (v-model="form.Name_Type")
        'category',
        'is_active'
    ];

    // แปลงค่า is_active เป็น Boolean อัตโนมัติ (0/1 -> false/true)
    // เพื่อให้ทำงานกับ Toggle Switch ใน Vue ได้ง่ายขึ้น
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
