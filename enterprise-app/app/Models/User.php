<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Hr\Branch;
use App\Models\Hr\Position;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // ระบุ Primary Key ให้ตรงกับ Migration
    protected $primaryKey = 'user_id';

    // เพิ่มรายชื่อฟิลด์ที่อนุญาตให้บันทึก (ต้องตรงกับ Database)
    protected $fillable = [
        'prefix',
        'first_name',
        'last_name',
        'id_card_number',
        'phone_number',
        'email',
        'password',
        'branch_id',
        'position_id',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ความสัมพันธ์ (Relationships)
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }
}
