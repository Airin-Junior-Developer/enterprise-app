<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\RequestType;

class RequestTypeController extends Controller
{
    // ดึงข้อมูลทั้งหมด
    public function indexAll()
    {
        return RequestType::orderBy('id', 'asc')->get();
    }

    // สร้างประเภทใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name_Type' => 'required|string|max:255'
        ]);

        $validated['is_active'] = 1; // 1 = เปิดใช้งาน (ถ้า DB เป็น boolean/tinyint)
        $type = RequestType::create($validated);

        return response()->json($type, 201);
    }

    // แก้ไขชื่อประเภท
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'Name_Type' => 'required|string|max:255'
        ]);

        $type = RequestType::findOrFail($id);
        $type->update($validated);

        return response()->json($type);
    }

    // สลับสถานะ (เปิด/ปิด)
    public function toggleStatus($id)
    {
        $type = RequestType::findOrFail($id);

        // สลับค่า (ถ้าเป็น 1 ให้เป็น 0, ถ้าเป็น 0 ให้เป็น 1)
        $type->is_active = $type->is_active ? 0 : 1;
        $type->save();

        return response()->json([
            'message' => 'อัปเดตสถานะสำเร็จ',
            'is_active' => $type->is_active
        ]);
    }
}