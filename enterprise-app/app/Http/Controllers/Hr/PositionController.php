<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Position;
use Illuminate\Support\Facades\Schema; // ✅ ตรวจสอบโครงสร้างตารางจริง

class PositionController extends Controller
{
    public function index()
    {
        try {
            // ดึงข้อมูลพร้อม Relation (ถ้ามี)
            return Position::with(['employmentType', 'employeeCategory'])
                ->orderBy('position_id', 'asc')
                ->get();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_name' => 'required|string|max:255',
            'level_code' => 'required|string',
        ]);

        try {
            // ✅ บันทึกเฉพาะฟิลด์ที่มีคอลัมน์อยู่ใน DB จริงๆ (ป้องกัน Error 1054)
            $columns = Schema::getColumnListing('positions');
            $dataToSave = array_intersect_key($request->all(), array_flip($columns));

            $position = Position::create($dataToSave);
            return response()->json(['message' => 'สร้างสำเร็จ', 'data' => $position], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Save Failed: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $position = Position::findOrFail($id);

            // ✅ กรองฟิลด์ก่อนอัปเดต เพื่อให้สถานะและข้อมูลอื่นๆ บันทึกผ่าน
            $columns = Schema::getColumnListing('positions');
            $dataToUpdate = array_intersect_key($request->all(), array_flip($columns));

            $position->update($dataToUpdate);
            return response()->json(['message' => 'แก้ไขสำเร็จ', 'data' => $position]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Update Failed: ' . $e->getMessage()], 500);
        }
    }
}