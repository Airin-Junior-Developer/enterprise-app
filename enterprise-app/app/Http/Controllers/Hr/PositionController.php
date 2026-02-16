<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Position;

class PositionController extends Controller
{
    // 1. ดึงข้อมูลทั้งหมด
    public function index()
    {
        return response()->json(Position::orderBy('position_id', 'desc')->get());
    }

    // 2. สร้างตำแหน่งใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_name' => 'required|string|max:255',
            'position_name_en' => 'nullable|string|max:255',
            'level_code' => 'required|string',
            'employment_type_id' => 'nullable|numeric',
            'employee_category_id' => 'nullable|numeric',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'is_active' => 'boolean' // รับค่า true/false
        ]);

        Position::create($validated);

        return response()->json(['message' => 'สร้างตำแหน่งสำเร็จ']);
    }

    // 3. อัปเดตข้อมูล
    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        $validated = $request->validate([
            'position_name' => 'required|string|max:255',
            'position_name_en' => 'nullable|string|max:255',
            'level_code' => 'required|string',
            'employment_type_id' => 'nullable|numeric',
            'employee_category_id' => 'nullable|numeric',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'is_active' => 'boolean'
        ]);

        $position->update($validated);

        return response()->json(['message' => 'แก้ไขข้อมูลสำเร็จ']);
    }

    // 4. ลบข้อมูล (เช็คก่อนว่ามีคนใช้ตำแหน่งนี้ไหม)
    public function destroy($id)
    {
        $position = Position::findOrFail($id);

        // ถ้ามีพนักงานใช้ตำแหน่งนี้อยู่ ห้ามลบ
        if ($position->users()->count() > 0) {
            return response()->json(['message' => 'ไม่สามารถลบได้ เนื่องจากมีพนักงานในตำแหน่งนี้'], 400);
        }

        $position->delete();

        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}
