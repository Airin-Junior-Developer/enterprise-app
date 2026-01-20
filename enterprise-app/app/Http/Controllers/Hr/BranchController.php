<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Branch;

class BranchController extends Controller
{
    // 1. ดึงข้อมูลสาขาทั้งหมด
    public function index()
    {
        $branches = Branch::all();
        return response()->json(['status' => 'success', 'data' => $branches]);
    }

    // 2. เพิ่มสาขาใหม่ (Create)
    public function store(Request $request)
    {
        $branch = Branch::create([
            'name' => $request->name,
            'code' => $request->code
        ]);

        return response()->json(['status' => 'success', 'message' => 'เพิ่มสาขาสำเร็จ']);
    }

    // 3. แก้ไขสาขา (Update)
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->update([
            'name' => $request->name,
            'code' => $request->code
        ]);

        return response()->json(['status' => 'success', 'message' => 'แก้ไขสาขาสำเร็จ']);
    }

    // 4. ลบสาขา (Delete)
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return response()->json(['status' => 'success', 'message' => 'ลบสาขาสำเร็จ']);
    }
}
