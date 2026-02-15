<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Request extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $primaryKey = 'request_id';

    protected $fillable = [
        'user_id',
        'request_type_id', // เปลี่ยนจาก request_type เป็น ID ตาม DB จริง
        'subject',         // ต้องมี เพราะใน DB ห้ามเป็น NULL
        'reason',
        'status',
        'start_date',
        'end_date',
        'amount',
        'approver_id',     // เพิ่มกลับเข้ามาเพื่อใช้บันทึกคนอนุมัติ
        'comment'          // เพิ่มเพื่อใช้บันทึกเหตุผลการอนุมัติ/ปฏิเสธ
    ];

    // ความสัมพันธ์: คำร้องนี้เป็นของพนักงานคนไหน
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // ความสัมพันธ์: ประเภทของคำร้อง (ลา, เบิก ฯลฯ)
    public function requestType()
    {
        return $this->belongsTo(RequestType::class, 'request_type_id', 'id');
    }

    // เพิ่มความสัมพันธ์ผู้อนุมัติกลับเข้ามา (เพราะในตารางมีรองรับแล้ว)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id', 'user_id');
    }
}