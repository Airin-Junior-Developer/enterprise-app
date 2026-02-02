<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\ViewEmployee;

class EmployeeController extends Controller
{
    public function index()
    {
        // 2. ดึงข้อมูลจาก View ได้เลย (ไม่ต้อง Join แล้ว โค้ดสั้นลงเยอะ!)
        $employees = ViewEmployee::all();

        return response()->json($employees);
    }

    public function store(Request $request)
    {
        // 1. ตรวจสอบข้อมูล (Validation)
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // ห้ามซ้ำ
            'branch_id' => 'required|exists:branches,branch_id',
            'position_id' => 'required|exists:positions,position_id',
            // บังคับใส่รหัสผ่าน และต้องตรงกับช่อง confirm
            'password' => 'required|string|min:6|confirmed',
            'id_card_number' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);

        // 2. เข้ารหัสรหัสผ่านก่อนบันทึก
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
            // เช็คอีเมลซ้ำ (ยกเว้นตัวเอง)
            'email' => 'required|email|unique:users,email,' . $id . ',user_id',
            'branch_id' => 'required|exists:branches,branch_id',
            'position_id' => 'required|exists:positions,position_id',
            // รหัสผ่านเป็น Optional (ใส่เมื่อต้องการเปลี่ยน)
            'password' => 'nullable|string|min:6|confirmed',
            'id_card_number' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);

        // ถ้ามีการส่ง password มาใหม่ ให้ Hash แล้วอัปเดต
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            // ถ้าไม่ส่งมา ให้ลบ key นี้ออก (ใช้รหัสเดิม)
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