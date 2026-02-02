<?php

namespace App\Http\Controllers; // ปกติ AuthController จะอยู่ที่นี่ (เช็ค Namespace อีกทีนะครับ)

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ViewEmployee; // เรียกใช้ Model ของ View

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'], 401);
        }

        // ดึง User ธรรมดามาเพื่อสร้าง Token (Eloquent Model ปกติ)
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        // ดึงข้อมูลพนักงานจาก View (เพื่อให้ได้ข้อมูลครบๆ เช่น position_name, branch_name)
        // ใช้ user_id ในการค้นหา
        $employeeData = ViewEmployee::where('user_id', $user->user_id)->first();

        return response()->json([
            'message' => 'Login success',
            'user' => $employeeData, // ส่งข้อมูลจาก View กลับไป Frontend
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request)
    {
        // แก้ให้ดึงจาก View แทนการใช้ $request->user()->load(...)
        // วิธีนี้จะลดการ JOIN database หน้าบ้าน และได้ข้อมูลที่ format สวยงามตาม View
        $user = ViewEmployee::where('user_id', $request->user()->user_id)->first();

        return response()->json($user);
    }
}