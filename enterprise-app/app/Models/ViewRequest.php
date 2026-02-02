<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewRequest extends Model
{
    protected $table = 'requests_list'; // ชี้ไปที่ View
    public $timestamps = false;         // View อ่านได้อย่างเดียว
}