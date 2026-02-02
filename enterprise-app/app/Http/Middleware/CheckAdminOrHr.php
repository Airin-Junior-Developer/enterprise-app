<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOrHr
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. เช็คว่าล็อกอินหรือยัง?
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // เช็คว่าเป็น Admin หรือ HR ไหม? (เรียกใช้ฟังก์ชันที่เราเพิ่งเขียนใน Model)
        if (!$request->user()->isAdminOrHr()) {
            // ถ้าไม่ใช่ ให้ตอบกลับว่า "ห้ามเข้า" (Forbidden)
            return response()->json(['message' => 'Access Denied: คุณไม่มีสิทธิ์เข้าถึงส่วนนี้'], 403);
        }

        // ถ้าผ่านเงื่อนไข ให้ไปต่อได้
        return $next($request);
    }
}