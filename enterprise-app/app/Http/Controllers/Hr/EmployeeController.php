<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // --- [GET] ดึงรายชื่อพนักงานทั้งหมด ---
    public function index()
    {
        // ใช้ with(['branch', 'position']) เพื่อ Join ตาราง
        // ทำให้ตอนเราเรียก employees.branch.branch_name ในหน้าเว็บ มันจะมีข้อมูลโชว์
        return User::with(['branch', 'position'])->get();
    }

    // --- [POST] เพิ่มพนักงานใหม่ ---
    public function store(Request $request)
    {
        // 1. ตรวจสอบความถูกต้องของข้อมูล (Validation)
        $validated = $request->validate([
            'first_name' => 'required',             // ชื่อห้ามเว้นว่าง
            'email' => 'required|email|unique:users', // อีเมลห้ามซ้ำ
            'branch_id' => 'required',              // ต้องเลือกสาขา
            'position_id' => 'required',            // ต้องเลือกตำแหน่ง
        ]);

        // 2. สร้าง User ใหม่
        $user = new User();
        $user->prefix = $request->prefix ?? 'คุณ';  // ถ้าไม่ใส่คำนำหน้า ให้เป็น "คุณ"
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name ?? ''; // นามสกุลถ้าไม่มี ให้เป็นค่าว่าง
        $user->email = $request->email;

        // ตั้งรหัสผ่านเริ่มต้นเป็น 12345678 (เข้ารหัสด้วย Hash)
        $user->password = Hash::make('12345678');

        $user->branch_id = $request->branch_id;
        $user->position_id = $request->position_id;
        $user->status = 'active'; // ตั้งสถานะเป็น "ปกติ"

        // 3. บันทึกลง Database
        $user->save();

        return response()->json($user, 201); // ส่งกลับ 201 (Created)
    }

    // --- [DELETE] ลบพนักงาน ---
    public function destroy($id)
    {
        // ค้นหาและลบ User ตาม ID ที่ส่งมา
        User::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
