<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
    public function index()
    {
        return response()->json([
            'employment_types' => DB::table('employment_types')->get(),
            'employee_categories' => DB::table('employee_categories')->get(),
        ]);
    }

    public function employmentTypes()
    {
        return response()->json(DB::table('employment_types')->get());
    }

    public function employeeCategories()
    {
        return response()->json(DB::table('employee_categories')->get());
    }
}
