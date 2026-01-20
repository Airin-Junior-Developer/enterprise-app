<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HrProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PersonnelController extends Controller
{
    // 1. ฟังก์ชันดึงรายชื่อพนักงาน
    public function index()
    {
        $employees = User::with(['branch', 'profile'])
            ->whereHas('profile') // ดึงเฉพาะคนที่มีประวัติพนักงาน
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $employees
        ]);
    }

    // 2. ฟังก์ชันเพิ่มพนักงาน (Transaction Save)
    public function store(Request $request)
    {
        // รับค่าจากหน้าบ้าน
        $data = $request->validate([
            'prefix' => 'nullable|string',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'position' => 'required|string',
            'branch_id' => 'required|exists:branches,id',
            'start_date' => 'required|date',
            'phone' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($data) {
            // 2.1 สร้าง User (Login)
            $user = User::create([
                'branch_id' => $data['branch_id'],
                'name' => $data['firstname'] . ' ' . $data['lastname'],
                'email' => $data['email'],
                'password' => Hash::make('password'), // รหัสผ่านเริ่มต้น
                'role_global' => 'user',
            ]);

            // 2.2 สร้างประวัติ (Profile)
            HrProfile::create([
                'user_id' => $user->id,
                'prefix' => $data['prefix'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'position' => $data['position'],
                'phone' => $data['phone'] ?? null,
                'start_date' => $data['start_date'],
            ]);

            return response()->json(['status' => 'success', 'message' => 'บันทึกข้อมูลสำเร็จ'], 201);
        });
    }

    // 4. ฟังก์ชันแก้ไขข้อมูล (Update)
    public function update(Request $request, $id)
    {
        // ค้นหาพนักงานก่อน (รวมประวัติด้วย)
        $user = User::with('profile')->findOrFail($id);

        // อัปเดตข้อมูล Login (User)
        $user->update([
            'name' => $request->firstname . ' ' . $request->lastname,
            'email' => $request->email,
            'branch_id' => $request->branch_id,
        ]);

        // อัปเดตข้อมูลประวัติ (Profile)
        // ใช้ updateOrCreate เผื่อว่าข้อมูลเก่าขาดหายไป
        HrProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'prefix' => $request->prefix,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'position' => $request->position,
                'phone' => $request->phone,
                'start_date' => $request->start_date,
            ]
        );

        return response()->json(['status' => 'success', 'message' => 'แก้ไขข้อมูลสำเร็จ']);
    }

    // 5. ฟังก์ชันลบข้อมูล (Delete)
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // ลบข้อมูล (Profile จะถูกลบอัตโนมัติถ้าตั้ง Database ไว้ดี แต่ลบเผื่อไว้ก่อนก็ได้)
        if ($user->profile) {
            $user->profile->delete();
        }
        $user->delete();

        return response()->json(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
    }
}
