<?php

namespace App\Http\Controllers\Hr; // ✅ Namespace ต้องเป็นแบบนี้

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ViewEmployee;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'], 401);
        }

        // ใช้ user_id หรือ id ตามโครงสร้าง Database ของคุณ
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        // ดึงข้อมูลพนักงานจาก View
        // ตรวจสอบว่า ViewEmployee ใช้ column 'user_id' หรือ 'id'
        $employeeData = ViewEmployee::where('user_id', $user->user_id)->first();

        return response()->json([
            'message' => 'Login success',
            'user' => $employeeData,
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
        // ดึงข้อมูลจาก View เช่นกัน
        $user = ViewEmployee::where('user_id', $request->user()->user_id)->first();
        return response()->json($user);
    }
}