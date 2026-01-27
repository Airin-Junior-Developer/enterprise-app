<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hr\Employee;
use App\Models\Hr\Branch;
use App\Models\Hr\Position;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. นับจำนวนทั้งหมด
        $totalEmployees = Employee::count();
        $totalBranches = Branch::count();
        $totalPositions = Position::count();

        // 2. ดึงรายชื่อพนักงาน 5 คนล่าสุด (เอาไปโชว์ในตารางหน้าแรก)
        $recentEmployees = Employee::with(['branch', 'position'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // ส่งข้อมูลกลับไปเป็น JSON
        return response()->json([
            'summary' => [
                'employees' => $totalEmployees,
                'branches' => $totalBranches,
                'positions' => $totalPositions,
                'pending' => 0 // สมมติไว้ก่อน (อนาคตค่อยทำระบบอนุมัติ)
            ],
            'recent_employees' => $recentEmployees
        ]);
    }
}