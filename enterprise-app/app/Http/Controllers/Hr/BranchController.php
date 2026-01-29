<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Branch;

class BranchController extends Controller
{
    // ดึงข้อมูลสาขาทั้งหมด
    public function index()
    {
        return Branch::all();
    }

    // เพิ่มสาขาใหม่
    public function store(Request $request)
    {
        // ตรวจสอบว่าต้องมีชื่อสาขา (branch_name)
        $request->validate(['branch_name' => 'required']);

        // สร้างและบันทึกข้อมูลทีเดียว (Mass Assignment)
        return Branch::create($request->all());
    }

    // ลบสาขา
    public function destroy($id)
    {
        Branch::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
