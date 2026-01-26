<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Branch;

class BranchController extends Controller
{
    /**
     * ดึงข้อมูลสาขาทั้งหมด
     * Method: GET
     */
    public function index()
    {
        // ดึงข้อมูลและเรียงลำดับจากสร้างล่าสุดไปเก่าสุด
        return response()->json(Branch::orderBy('created_at', 'desc')->get());
    }

    /**
     * เพิ่มสาขาใหม่
     * Method: POST
     */
    public function store(Request $request)
    {
        // สร้างข้อมูลใหม่ตาม Request ที่ส่งมา
        $branch = Branch::create($request->all());
        return response()->json(['message' => 'Created', 'data' => $branch]);
    }

    /**
     * แก้ไขข้อมูลสาขา
     * Method: PUT/PATCH
     */
    public function update(Request $request, string $id)
    {
        // ค้นหาสาขาตาม ID หากไม่เจอจะแจ้ง Error 404
        $branch = Branch::findOrFail($id);
        $branch->update($request->all());
        return response()->json(['message' => 'Updated']);
    }

    /**
     * ลบสาขา
     * Method: DELETE
     */
    public function destroy(string $id)
    {
        // ลบข้อมูลตาม ID ที่ระบุ
        Branch::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
