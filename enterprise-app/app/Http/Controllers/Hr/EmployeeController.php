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
        try {
            // ดึงข้อมูลจาก View เพื่อโชว์ในตาราง
            return response()->json(ViewEmployee::all());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Database Error: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $employee = ViewEmployee::where('user_id', $id)->firstOrFail();
            return response()->json($employee);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not Found'], 404);
        }
    }

    public function store(Request $request)
    {
        // ✅ 1. ปรับ Validation ให้ยืดหยุ่นขึ้นเพื่อ Debug 
        // หากติด error 'id not exists' แสดงว่า ID ที่ส่งมาไม่มีอยู่ในตารางนั้นจริงๆ
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'branch_id' => 'required',
            'position_id' => 'required',
            'employment_type_id' => 'required',
            'employee_category_id' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'id_card_number' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);

        try {
            // 2. เข้ารหัสรหัสผ่าน
            $validated['password'] = Hash::make($request->password);

            // 3. สร้าง User
            User::create($validated);

            return response()->json(['message' => 'เพิ่มพนักงานสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Save Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::where('user_id', $id)->firstOrFail();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',user_id',
            'branch_id' => 'required',
            'position_id' => 'required',
            'employment_type_id' => 'required',
            'employee_category_id' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'id_card_number' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        try {
            $user->update($validated);
            return response()->json(['message' => 'อัปเดตข้อมูลสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Update Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::where('user_id', $id)->firstOrFail();
            $user->delete();
            return response()->json(['message' => 'ลบข้อมูลสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Delete Error: ' . $e->getMessage()], 500);
        }
    }
}