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
            'is_notify_expired' => auth()->user() ? auth()->user()->is_notify_expired : 0
        ]);
    }
}