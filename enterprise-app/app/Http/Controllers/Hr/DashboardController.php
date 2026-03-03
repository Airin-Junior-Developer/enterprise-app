<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ViewRequest; // ใช้ View ที่เราทำไว้
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
    //  แอบสลับตำแหน่งคืนให้คนหมดเวลา (ก่อนโหลดข้อมูลหน้า Dashboard)
        \Illuminate\Support\Facades\DB::table('users')
            ->whereNotNull('temp_position_end_date')
            ->whereDate('temp_position_end_date', '<', now())
            ->update([
                'position_id' => \Illuminate\Support\Facades\DB::raw('original_position_id'),
                'original_position_id' => null,
                'temp_position_end_date' => null,
                'is_notify_expired' => 1
            ]);

    // 1. ดึงข้อมูลตัวเลขสถิติ (Stats) ให้ตรงกับชื่อตัวแปรใน Vue
        $stats = [
            // 1. นับเฉพาะพนักงานที่ยังทำงานอยู่จริง (ใช้ Active ตัวพิมพ์ใหญ่ตาม Enum ใน DB)
            'employees' => User::where('status', 'Active')->count(),

            'requests_total' => ViewRequest::count(),

            // 2. แก้ไข 'pending' -> 'Pending' และ 'approved' -> 'Approved'
            'requests_pending' => ViewRequest::where('status', 'Pending')->count(),
            'requests_approved' => ViewRequest::where('status', 'Approved')->count(),
        ];

        // 3. ตรวจสอบให้แน่ใจว่าใน SQL View ได้เพิ่ม SELECT `r`.`subject` เข้าไปด้วยแล้ว
        $recent_requests = ViewRequest::orderBy('created_at', 'desc')->take(5)->get();

        return response()->json([
            'stats' => $stats,
            'recent_requests' => $recent_requests,
            // (ใช้ ->fresh() เพื่อดึงค่าที่เพิ่งอัปเดตใหม่สดๆ)
            'is_notify_expired' => auth()->user() ? auth()->user()->fresh()->is_notify_expired : 0
        ]);
    }
}