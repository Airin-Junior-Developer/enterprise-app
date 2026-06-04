<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\LogsActivity;

class EmployeeSalaryHistory extends Model
{
    use LogsActivity;

    protected static string $auditModule = 'HR';

    protected $fillable = [
        'user_id',
        'effective_date',
        'old_salary',
        'new_salary',
        'promotion_type',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'old_salary' => 'decimal:2',
        'new_salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by', 'user_id');
    }
}
