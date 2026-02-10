<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Position;

class PositionController extends Controller
{
    public function index()
    {
        // ✅ เพิ่ม with(...) เพื่อดึงชื่อประเภทการจ้าง/พนักงาน มาแสดงในตาราง
        return Position::with(['employmentType', 'employeeCategory'])
            ->orderBy('level', 'asc')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_name' => 'required|string|max:255',
            'position_name_en' => 'nullable|string|max:255',
            'level_code' => 'required|string',
            'employment_type_id' => 'nullable|integer',   // ✅ รับเป็น ID
            'employee_category_id' => 'nullable|integer', // ✅ รับเป็น ID
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'is_active' => 'boolean'
        ]);

        $priorityMap = ['CEO' => 1, 'M' => 5, 'O1' => 10];
        $validated['level'] = $priorityMap[$validated['level_code']] ?? 99;

        $position = Position::create($validated);
        return response()->json(['message' => 'สร้างสำเร็จ', 'data' => $position], 201);
    }

    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        $validated = $request->validate([
            'position_name' => 'required|string|max:255',
            'position_name_en' => 'nullable|string|max:255',
            'level_code' => 'required|string',
            'employment_type_id' => 'nullable|integer',   // ✅ รับเป็น ID
            'employee_category_id' => 'nullable|integer', // ✅ รับเป็น ID
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'is_active' => 'boolean'
        ]);

        $priorityMap = ['CEO' => 1, 'M' => 5, 'O1' => 10];
        $validated['level'] = $priorityMap[$validated['level_code']] ?? 99;

        $position->update($validated);
        return response()->json(['message' => 'แก้ไขสำเร็จ', 'data' => $position]);
    }

    public function destroy($id)
    {
        Position::destroy($id);
        return response()->json(['message' => 'ลบสำเร็จ']);
    }
}