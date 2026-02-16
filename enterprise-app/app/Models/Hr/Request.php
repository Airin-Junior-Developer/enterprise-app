<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $table = 'requests';
    protected $primaryKey = 'request_id'; // ✅ สำคัญ: ต้องระบุ PK เพราะไม่ใช่ 'id'

    protected $fillable = [
        'user_id',
        'request_type_id',
        'subject',
        'reason',
        'start_date',
        'end_date',
        'amount',
        'attachment',
        'status',
        'approver_id',
        'comment'
    ];

    // จัดการเรื่องวันที่อัตโนมัติ
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
