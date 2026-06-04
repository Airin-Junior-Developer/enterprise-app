<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\LogsActivity;

class EmployeeEducation extends Model
{
    use LogsActivity;

    protected $table = 'employee_educations';

    protected static string $auditModule = 'HR';

    protected $fillable = [
        'user_id',
        'institution_name',
        'degree_level',
        'field_of_study',
        'graduation_year',
        'gpa',
        'notes',
    ];

    protected $casts = [
        'graduation_year' => 'integer',
        'gpa' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
