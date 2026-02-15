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
        // ตรวจสอบทั้ง Email และ Status ต้องเป็น Active เท่านั้น
        $user = User::where('email', $request->email)
            ->where('status', 'Active')
            ->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง หรือบัญชีถูกระงับ'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
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