<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\LogsActivity;

class EmployeeWorkHistory extends Model
{
    use LogsActivity;

    protected static string $auditModule = 'HR';

    protected $fillable = [
        'user_id',
        'company_name',
        'position_title',
        'start_date',
        'end_date',
        'reason_for_leaving',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
