<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Branch; // เรียก Model Branch

class BranchController extends Controller
{
    // 1. ดึงข้อมูลสาขาทั้งหมด
    public function index()
    {
        // เรียงตามชื่อสาขา
        return response()->json(Branch::orderBy('branch_name')->get());
    }

    // 2. สร้างสาขาใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_name' => 'required|string|max:255|unique:branches,branch_name',
            'address' => 'nullable|string'
        ]);

        Branch::create($validated);

        return response()->json(['message' => 'เพิ่มสาขาสำเร็จ'], 201);
    }

    // 3. แก้ไขข้อมูลสาขา
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $validated = $request->validate([
            'branch_name' => 'required|string|max:255|unique:branches,branch_name,' . $id . ',branch_id',
            'address' => 'nullable|string'
        ]);

        $branch->update($validated);

        return response()->json(['message' => 'แก้ไขข้อมูลสำเร็จ']);
    }

    // 4. ลบสาขา
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);

        // เช็คก่อนว่ามีพนักงานสังกัดสาขานี้ไหม ถ้ามีห้ามลบ
        if ($branch->users()->count() > 0) {
            return response()->json(['message' => 'ไม่สามารถลบได้ เนื่องจากมีพนักงานสังกัดสาขานี้อยู่'], 400);
        }

        $branch->delete();

        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}
