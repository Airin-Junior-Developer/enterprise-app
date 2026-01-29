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
            // 1. นับจำนวนพนักงานทั้งหมดในระบบ (จากตาราง users)
            'employees_count' => User::count(),

            // 2. นับจำนวนสาขาทั้งหมด (จากตาราง branches)
            'branches_count' => Branch::count(),

            // 3. นับจำนวนตำแหน่งงาน (จากตาราง positions)
            'positions_count' => Position::count(),

            // 4. สถานะระบบ (Hardcode ไว้ก่อนว่า Online)
            'system_status' => 'Online',

            // 5. ดึงรายชื่อพนักงาน 5 คนที่เพิ่งสมัครเข้ามาล่าสุด
            // - with(...): สั่งให้ไปหยิบชื่อสาขา (branch) และชื่อตำแหน่ง (position) มาด้วยเลย จะได้ไม่ต้อง Query หลายรอบ
            // - orderBy('created_at', 'desc'): เรียงจากใหม่ไปเก่า
            // - take(5): เอาแค่ 5 คนพอ
            'recent_employees' => User::with(['branch', 'position'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ]);
    }
}
