<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $primaryKey = 'branch_id'; // ระบุ Primary Key

    protected $fillable = [
        'branch_name', // แก้เป็น branch_name
        'description'
    ];

    // ความสัมพันธ์กับ User
    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'branch_id', 'branch_id');
    }
}
