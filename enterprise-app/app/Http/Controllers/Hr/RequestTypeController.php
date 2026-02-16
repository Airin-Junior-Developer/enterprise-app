<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\RequestType;

class RequestTypeController extends Controller
{
    // 1. ดึงข้อมูลทั้งหมด (รวมที่ปิดใช้งานด้วย) สำหรับหน้าจัดการ
    public function indexAll()
    {
        // เรียงตาม ID ล่าสุด
        $types = RequestType::orderBy('id', 'desc')->get();
        return response()->json($types);
    }

    // 2. เพิ่มประเภทใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name_Type' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        RequestType::create($validated);

        return response()->json(['message' => 'เพิ่มประเภทคำร้องสำเร็จ']);
    }

    // 3. แก้ไขชื่อประเภท
    public function update(Request $request, $id)
    {
        $type = RequestType::findOrFail($id);

        $validated = $request->validate([
            'Name_Type' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $type->update($validated);

        return response()->json(['message' => 'แก้ไขข้อมูลสำเร็จ']);
    }

    // 4. เปลี่ยนสถานะ เปิด/ปิด (Toggle Status)
    public function toggleStatus(Request $request, $id)
    {
        $type = RequestType::findOrFail($id);

        // รับค่า is_active ที่ส่งมาจาก Vue
        $type->is_active = $request->input('is_active');
        $type->save();

        return response()->json([
            'message' => 'อัปเดตสถานะสำเร็จ',
            'is_active' => $type->is_active
        ]);
    }
}
