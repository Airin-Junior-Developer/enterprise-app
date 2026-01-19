<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrProfile extends Model
{
    use HasFactory;

    protected $table = 'hr_profiles';

    protected $fillable = [
        'user_id', 
        'prefix', 
        'firstname', 
        'lastname', 
        'position', 
        'phone', 
        'start_date'
    ];

    // เชื่อมกลับไปหา User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}