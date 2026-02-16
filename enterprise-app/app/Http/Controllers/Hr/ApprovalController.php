<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Hr\Request as RequestModel; // เรียก Model ของตารางจริงเพื่อใช้อัปเดต

class ApprovalController extends Controller
{
    // 1. ดึงรายการคำร้องทั้งหมด (ใช้ View: requests_list)
    public function index(Request $request)
    {
        // ดึงจาก View ที่เราสร้างไว้ใน Navicat (เร็วและง่ายกว่า Eloquent Join)
        $requests = DB::table('requests_list')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($requests);
    }

    // 2. อัปเดตสถานะ (อนุมัติ / ไม่อนุมัติ)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected'
        ]);

        // หาใบคำร้องจากตารางจริง (requests)
        $doc = RequestModel::findOrFail($id);

        // อัปเดตสถานะและคนอนุมัติ
        $doc->update([
            'status' => $request->status,
            'approver_id' => $request->user()->user_id, // เก็บ ID คนกดอนุมัติ (คนที่ Login อยู่)
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'บันทึกสถานะเรียบร้อยแล้ว']);
    }
}
