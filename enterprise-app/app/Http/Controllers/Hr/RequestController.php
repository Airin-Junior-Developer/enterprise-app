<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Hr\Request as RequestModel;

class RequestController extends Controller
{
    // 1. ดึงรายการคำร้อง "ของฉัน" (My Requests)
    public function index(Request $request)
    {
        $userId = $request->user()->user_id;

        // ดึงจาก View ที่เราสร้างไว้ (requests_list) เพื่อให้ได้ชื่อประเภทและสถานะครบ
        // แต่ Filter เอาเฉพาะของ User คนปัจจุบัน
        $requests = DB::table('requests_list')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($requests);
    }

    // 2. ดึงประเภทคำร้อง (สำหรับ Dropdown)
    public function getTypes()
    {
        // ดึงเฉพาะที่เปิดใช้งาน (is_active = 1)
        $types = DB::table('requests_type')
            ->where('is_active', 1)
            ->get();

        return response()->json($types);
    }

    // 3. สร้างคำร้องใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_type_id' => 'required',
            'subject' => 'required|string|max:255',
            'reason' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date', // วันจบต้องไม่ก่อนวันเริ่ม
            'amount' => 'nullable|numeric'
        ]);

        // เติมข้อมูลที่เหลือ
        $validated['user_id'] = $request->user()->user_id;
        $validated['status'] = 'Pending'; // เริ่มต้นเป็น "รออนุมัติ" เสมอ
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        RequestModel::create($validated);

        return response()->json(['message' => 'สร้างคำร้องสำเร็จ']);
    }

    // 4. ยกเลิก/ลบคำร้อง (เฉพาะสถานะ Pending)
    public function destroy(Request $request, $id)
    {
        $userId = $request->user()->user_id;

        // เช็คว่าเป็นของเจ้าตัวจริงไหม และสถานะยังรออนุมัติอยู่ไหม
        $req = RequestModel::where('request_id', $id)
            ->where('user_id', $userId)
            ->where('status', 'Pending')
            ->first();

        if (!$req) {
            return response()->json(['message' => 'ไม่สามารถลบได้ (เอกสารอาจถูกอนุมัติไปแล้ว หรือไม่ใช่ของคุณ)'], 403);
        }

        $req->delete();

        return response()->json(['message' => 'ลบคำร้องสำเร็จ']);
    }

    // 5. อนุมัติ/ไม่อนุมัติ (สำหรับหัวหน้า) - ใช้ร่วมกับ Route ที่มี Middleware
    public function updateStatus(Request $request, $id)
    {
        // ฟังก์ชันนี้เรามี ApprovalController ทำหน้าที่แทนแล้วใน Route กลุ่มอื่น
        // แต่ถ้าจะใช้ในอนาคตก็ใส่ Logic ตรงนี้ได้ครับ
    }
}
