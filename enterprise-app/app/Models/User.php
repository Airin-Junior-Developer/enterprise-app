<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'branch_id',    // <-- สำคัญ: ต้องใส่เพื่อให้อัปเดตสาขาได้
        'name',
        'email',
        'password',
        'role_global',  // <-- สำคัญ: สิทธิ์ admin
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ** เพิ่มฟังก์ชันนี้: เพื่อบอกว่า User นี้สังกัดสาขาอะไร **
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // ** เพิ่มฟังก์ชันนี้: เพื่อดึงประวัติส่วนตัว **
    public function profile()
    {
        return $this->hasOne(Employee::class);
    }
}
