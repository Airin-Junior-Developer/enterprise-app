<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\ViewEmployee;

class EmployeeController extends Controller
{
    // ดึงข้อมูลทั้งหมด (สำหรับตาราง)
    public function index()
    {
        $employees = ViewEmployee::all();
        return response()->json($employees);
    }

    // เพิ่มฟังก์ชัน show: ดึงข้อมูลรายคนจาก View (สำหรับ Modal แก้ไข)
    public function show($id)
    {
        // ใช้ ViewEmployee แทน User::find() เพื่อลดการ JOIN หน้าบ้าน
        // ข้อมูลที่ได้จะมีทั้ง branch_name, position_name มาให้เลย
        $employee = ViewEmployee::where('user_id', $id)->firstOrFail();

        return response()->json($employee);
    }

    public function store(Request $request)
    {
        // 1. ตรวจสอบข้อมูล (Validation)
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'branch_id' => 'required|exists:branches,branch_id',
            'position_id' => 'required|exists:positions,position_id',
            'password' => 'required|string|min:6|confirmed',
            'id_card_number' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);

        // 2. เข้ารหัสรหัสผ่าน
        $validated['password'] = Hash::make($request->password);

        // 3. สร้าง User
        User::create($validated);

        return response()->json(['message' => 'เพิ่มพนักงานสำเร็จ']);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',user_id',
            'branch_id' => 'required|exists:branches,branch_id',
            'position_id' => 'required|exists:positions,position_id',
            'password' => 'nullable|string|min:6|confirmed',
            'id_card_number' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message' => 'อัปเดตข้อมูลสำเร็จ']);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
    }
}