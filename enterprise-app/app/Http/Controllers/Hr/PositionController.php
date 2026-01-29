<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Position;

class PositionController extends Controller
{
    // ดึงตำแหน่งงานทั้งหมด
    public function index()
    {
        return Position::all();
    }

    // เพิ่มตำแหน่งงานใหม่
    public function store(Request $request)
    {
        // ตรวจสอบว่าต้องมีชื่อตำแหน่ง (position_name)
        $request->validate(['position_name' => 'required']);

        // บันทึกลง Database
        return Position::create($request->all());
    }

    // ลบตำแหน่งงาน
    public function destroy($id)
    {
        Position::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
