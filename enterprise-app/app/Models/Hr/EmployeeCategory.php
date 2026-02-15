<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // อย่าลืม Import Model User

class EmployeeCategory extends Model
{
    use HasFactory;

    protected $table = 'employee_categories';

    protected $fillable = ['name'];

    // เพิ่มความสัมพันธ์: 1 หมวดหมู่ มีพนักงานได้หลายคน
    public function users()
    {
        return $this->hasMany(User::class, 'employee_category_id', 'id');
    }
}