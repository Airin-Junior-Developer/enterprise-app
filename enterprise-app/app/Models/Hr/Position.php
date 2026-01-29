<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $primaryKey = 'position_id'; // ระบุ Primary Key

    protected $fillable = [
        'position_name', // ✅ แก้เป็น position_name
        'description'
    ];
}
