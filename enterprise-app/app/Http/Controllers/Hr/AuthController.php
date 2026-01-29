<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. ตรวจสอบข้อมูลที่ส่งมา
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. พยายามล็อกอิน
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 3. สร้าง Token (บัตรผ่าน)
            $token = $user->createToken('hr-system-token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token // ส่งกุญแจกลับไปให้หน้าบ้าน
            ]);
        }

        // 4. ถ้าล็อกอินไม่ผ่าน
        return response()->json(['message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
