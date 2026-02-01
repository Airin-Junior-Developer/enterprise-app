<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Request extends Model
{
    use HasFactory;

    protected $table = 'requests';

    // ระบุ Primary Key ให้ตรงกับ Migration
    protected $primaryKey = 'request_id';

    // แก้ไข $fillable ให้ตรงกับชื่อคอลัมน์ในฐานข้อมูลจริง
    protected $fillable = [
        'user_id',
        'request_type', // แก้จาก type เป็น request_type
        'reason',
        'status',
        'start_date',   // เพิ่มฟิลด์วันที่
        'end_date',
        'amount'        // เพิ่มฟิลด์จำนวนเงิน
    ];

    // ความสัมพันธ์: คำร้องนี้เป็นของ User คนไหน
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // หมายเหตุ: ตัด relation branch และ approver ออกก่อน 
    // เพราะใน Migration ล่าสุดเรายังไม่ได้สร้างคอลัมน์ branch_id และ approver_id ในตาราง requests
    // (เราดู branch ได้จาก $request->user->branch แทนครับ)
}