<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Request as RequestModel; // ตั้งชื่อเล่น (Alias) เพื่อไม่ให้ชนกับ Request ของ Laravel
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    // 1. ดึงรายการคำร้อง (ของตัวเอง)
    public function index()
    {
        $user = Auth::user();

        // ดึงคำร้องทั้งหมดที่เป็นของ User คนนี้ พร้อมข้อมูลพนักงานและสาขา
        $requests = RequestModel::with(['user', 'branch'])
            ->where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($requests);
    }

    // 2. สร้างคำร้องใหม่
    public function store(Request $request)
    {
        $user = Auth::user();

        // ตรวจสอบความถูกต้อง
        $validated = $request->validate([
            'type' => 'required|string',
            'reason' => 'required|string',
            'details' => 'required|array', // ต้องส่งมาเป็น JSON Object
        ]);

        // บันทึกลงฐานข้อมูล
        $newRequest = RequestModel::create([
            'user_id' => $user->user_id,
            'branch_id' => $user->branch_id, // ใช้สาขาปัจจุบันของพนักงาน
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'details' => $validated['details'], // Laravel จะแปลง array เป็น json ให้เอง (เพราะเราทำ cast ไว้ใน Model)
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Created successfully',
            'data' => $newRequest
        ]);
    }
}
