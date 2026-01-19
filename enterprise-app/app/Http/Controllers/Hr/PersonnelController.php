<?php

namespace App\Http\Controllers\Hr; // <-- ต้องมี Hr

use App\Http\Controllers\Controller; // <-- เรียกใช้ไฟล์แม่แบบที่คุณเปิดเมื่อกี้
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HrProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PersonnelController extends Controller // <-- ชื่อ class ต้องตรงกับชื่อไฟล์
{
    // 1. ฟังก์ชันดึงรายชื่อพนักงาน
    public function index()
    {
        $employees = User::with(['branch', 'profile'])
                         ->has('profile')
                         ->get();

        return response()->json([
            'status' => 'success',
            'data' => $employees
        ]);
    }

    // 2. ฟังก์ชันเพิ่มพนักงาน (Store) ... (โค้ดเดิม)
    public function store(Request $request)
    {
        // ... (ใส่โค้ดเดิมที่เคยให้ไป) ...
    }
}