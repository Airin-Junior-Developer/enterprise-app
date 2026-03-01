<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. ดึงข้อมูลตัวเลขสถิติ (Stats) ให้ตรงกับชื่อตัวแปรใน Vue
        $stats = [
            'employees' => User::count(), // นับพนักงาน
            'requests_total' => DB::table('requests')->count(), // นับคำร้องทั้งหมด
            'requests_pending' => DB::table('requests')->where('status', 'Pending')->count(), // รออนุมัติ
            'requests_approved' => DB::table('requests')->where('status', 'Approved')->count(), // อนุมัติแล้ว
        ];

        // 2. ดึงรายการคำร้องล่าสุด 5 รายการ (ไม่ใช่พนักงานล่าสุด)
        // ต้อง Join ตารางเพื่อให้ได้ชื่อคน (users) และชื่อประเภทคำร้อง (requests_type)
        $recentRequests = DB::table('requests')
            ->join('users', 'requests.user_id', '=', 'users.user_id')
            ->join('requests_type', 'requests.request_type_id', '=', 'requests_type.id')
            ->select(
                'requests.request_id',
                'requests.status',
                'requests.created_at',
                'requests.amount',
                'requests.reason',
                'requests.start_date',
                'requests.end_date',
                'users.first_name',
                'users.last_name',
                'requests_type.Name_Type as request_type_name' // ตั้งชื่อใหม่ให้ตรงกับ Vue
            )
            ->orderBy('requests.created_at', 'desc')
            ->limit(5)
            ->get();

        // ส่งกลับเป็น JSON ตามโครงสร้างที่ Vue รอรับเป๊ะๆ
        return response()->json([
            'stats' => $stats,
<<<<<<< HEAD
            'recent_requests' => $recentRequests
=======
            'recent_requests' => $recent_requests,
            'is_notify_expired' => auth()->user() ? auth()->user()->is_notify_expired : 0
>>>>>>> origin/sea
        ]);
    }
}
