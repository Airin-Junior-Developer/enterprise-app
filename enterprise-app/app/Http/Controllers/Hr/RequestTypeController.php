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
        // เรียงจากเก่าไปใหม่ (รายการใหม่จะอยู่ล่างสุด)
        return response()->json(RequestType::orderBy('id', 'asc')->get());
    }

    // 2. เพิ่มประเภทใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name_Type' => 'required|string|max:255',
            'category' => 'required|in:date,money',
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
            'category' => 'required|in:date,money',
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

    // 5. ลบประเภทคำร้อง
    public function destroy($id)
    {
        try {
            $type = RequestType::findOrFail($id);
            $type->delete();
            return response()->json(['message' => 'ลบประเภทคำร้องสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'ไม่สามารถลบได้ (อาจมีข้อมูลคำร้องที่ใช้ประเภทนี้อยู่)'], 500);
        }
    }
}
