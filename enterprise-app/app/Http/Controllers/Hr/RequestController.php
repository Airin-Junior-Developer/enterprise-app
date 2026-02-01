<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Request as RequestModel; // ตั้งชื่อเล่น Model เพื่อไม่ให้ชนกับ Request หลัก
use App\Models\User;

class RequestController extends Controller
{
    // ดึงข้อมูลคำร้องทั้งหมด
    public function index()
    {
        // with('user') คือการดึงข้อมูลคนขอมาด้วย (Join ตาราง)
        $requests = RequestModel::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($requests);
    }

    // สร้างคำร้องใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'request_type' => 'required|string',
            'reason' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'amount' => 'nullable|numeric',
        ]);

        // ตั้งสถานะเริ่มต้นเป็น pending (รออนุมัติ)
        $validated['status'] = 'pending';

        RequestModel::create($validated);

        return response()->json(['message' => 'สร้างคำร้องสำเร็จ']);
    }

    // อัปเดตข้อมูล (แก้ไข หรือ เปลี่ยนสถานะ)
    public function update(Request $request, $id)
    {
        $req = RequestModel::findOrFail($id);

        // ถ้ามีการส่ง status มา (เช่น กดอนุมัติ)
        if ($request->has('status')) {
            $req->update(['status' => $request->status]);
        } else {
            // ถ้าแก้ไขเนื้อหาปกติ
            $req->update($request->all());
        }

        return response()->json(['message' => 'อัปเดตข้อมูลสำเร็จ']);
    }

    // ลบคำร้อง
    public function destroy($id)
    {
        RequestModel::destroy($id);
        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}