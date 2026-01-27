<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Position;

class PositionController extends Controller
{
    // แสดงรายการทั้งหมด
    public function index()
    {
        return response()->json(Position::orderBy('created_at', 'desc')->get());
    }

    // เพิ่มข้อมูลใหม่
    public function store(Request $request)
    {
        $position = Position::create($request->all());
        return response()->json(['message' => 'Created', 'data' => $position]);
    }

    // อัปเดตข้อมูล
    public function update(Request $request, string $id)
    {
        $position = Position::findOrFail($id);
        $position->update($request->all());
        return response()->json(['message' => 'Updated']);
    }

    // ลบข้อมูล
    public function destroy(string $id)
    {
        Position::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}