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
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        // ✅ เพิ่มบรรทัดนี้: สั่งให้โหลดข้อมูล "ตำแหน่ง" และ "สาขา" ติดตัว User ไปด้วย
        $user->load(['position', 'branch']);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'user' => $user, // ตอนนี้ในตัวแปร user จะมี position ติดไปด้วยแล้ว
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        // ลบ Token ทั้งหมดของ User นี้
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request)
    {
        // แก้ตรงนี้ด้วย: เวลาเรียกดูข้อมูล User ก็ให้โหลด Position เสมอ
        return $request->user()->load(['position', 'branch']);
    }
}