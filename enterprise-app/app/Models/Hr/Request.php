<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $table = 'requests';

    // ระบุ Primary Key ใหม่
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'user_id',
        'branch_id',
        'type',             // ประเภท: leave, salary, etc.
        'details',          // JSON รายละเอียด
        'reason',
        'status',           // pending, approved, rejected
        'approver_id',
        'approver_note'
    ];

    // แปลง JSON Details ให้เป็น Array อัตโนมัติ
    protected $casts = [
        'details' => 'array',
    ];

    // ความสัมพันธ์: คำร้องนี้เป็นของ User คนไหน
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'user_id');
    }

    // ความสัมพันธ์: คำร้องนี้สังกัดสาขาไหน
    public function branch()
    {
        return $this->belongsTo(\App\Models\Hr\Branch::class, 'branch_id', 'branch_id');
    }

    // ความสัมพันธ์: ใครเป็นคนอนุมัติ
    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approver_id', 'user_id');
    }
}
