<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // 1. ฟังก์ชันเข้าสู่ระบบ (Login)
    public function login(Request $request)
    {
        // ตรวจสอบค่าที่ส่งมา
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // ลองล็อกอินด้วย Auth::attempt (Laravel จะเช็ค Hash รหัสผ่านให้อัตโนมัติ)
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'], 401);
        }

        // ถ้าผ่าน ให้ดึงข้อมูล User ออกมา
        $user = User::where('email', $request->email)->first();

        // โหลดข้อมูลเสริมที่จำเป็นต้องใช้หน้าบ้าน (ตำแหน่ง, สาขา)
        $user->load('position', 'branch');

        // สร้าง Token (ใช้ชื่อว่า auth_token)
        $token = $user->createToken('auth_token')->plainTextToken;

        // ส่งกลับไปให้ Vue
        return response()->json([
            'message' => 'เข้าสู่ระบบสำเร็จ',
            'token' => $token,
            'user' => $user
        ]);
    }

    // 2. ฟังก์ชันออกจากระบบ (Logout)
    public function logout(Request $request)
    {
        // ลบ Token ทั้งหมดของ User คนนี้ (เพื่อให้ Token เก่าใช้ไม่ได้อีก)
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'ออกจากระบบสำเร็จ']);
    }

    // 3. ดึงข้อมูล User ปัจจุบัน (Get User Profile)
    // เผื่อใช้ตอนกด Refresh หน้าเว็บ แล้วอยากเช็คว่า Token ยังดีอยู่ไหม
    public function user(Request $request)
    {
        $user = $request->user();
        $user->load('position', 'branch'); // โหลดตำแหน่งมาด้วยเสมอ
        return response()->json($user);
    }
}
