<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hr\Branch;
use App\Models\Hr\Position;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'employees_count' => User::count(),
            'branches_count' => Branch::count(),
            'positions_count' => Position::count(),
            'system_status' => 'Online',
            // ดึงข้อมูล 5 คนล่าสุด พร้อม Join ตาราง branch และ position
            'recent_employees' => User::with(['branch', 'position'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ]);
    }
}