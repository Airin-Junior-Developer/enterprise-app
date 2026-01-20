<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Position;

class PositionController extends Controller
{
    // 1. ดึงข้อมูลตำแหน่งทั้งหมด
    public function index()
    {
        $positions = Position::all();
        return response()->json(['status' => 'success', 'data' => $positions]);
    }

    // 2. เพิ่มตำแหน่งใหม่
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        Position::create([
            'name' => $request->name
        ]);

        return response()->json(['status' => 'success', 'message' => 'บันทึกสำเร็จ']);
    }

    // 3. แก้ไขตำแหน่ง
    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);
        $position->update([
            'name' => $request->name
        ]);

        return response()->json(['status' => 'success', 'message' => 'แก้ไขสำเร็จ']);
    }

    // 4. ลบตำแหน่ง
    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return response()->json(['status' => 'success', 'message' => 'ลบสำเร็จ']);
    }
}
