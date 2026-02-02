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
        // 1. นับจำนวนต่างๆ
        $stats = [
            'employees' => User::count(),
            'requests_total' => ViewRequest::count(),
            'requests_pending' => ViewRequest::where('status', 'pending')->count(),
            'requests_approved' => ViewRequest::where('status', 'approved')->count(),
        ];

        // 2. ดึงคำร้องล่าสุด 5 รายการ (Recent Activities)
        $recent_requests = ViewRequest::orderBy('created_at', 'desc')->take(5)->get();

        return response()->json([
            'stats' => $stats,
            'recent_requests' => $recent_requests
        ]);
    }
}