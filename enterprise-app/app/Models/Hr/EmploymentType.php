<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EmploymentType extends Model
{
    use HasFactory;

    protected $table = 'employment_types';

    protected $fillable = ['name'];

    // เพิ่มความสัมพันธ์: 1 ประเภทการจ้างงาน มีพนักงานได้หลายคน
    public function users()
    {
        return $this->hasMany(User::class, 'employment_type_id', 'id');
    }
}