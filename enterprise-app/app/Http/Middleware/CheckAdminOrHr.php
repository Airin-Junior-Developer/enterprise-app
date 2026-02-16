<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOrHr
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->position) {
            return response()->json(['message' => 'Access Denied: ไม่พบข้อมูลตำแหน่ง'], 403);
        }

        // แปลงเป็นตัวเล็กทั้งหมด เพื่อกันปัญหาพิมพ์เล็ก/ใหญ่
        $posName = strtolower(trim($user->position->position_name));

        // ✅ ตรวจสอบว่ามี 'super admin' ในนี้หรือยัง
        $allowedRoles = ['super admin', 'system admin', 'hr manager'];

        if (in_array($posName, $allowedRoles)) {
            return $next($request);
        }

        return response()->json(['message' => 'Access Denied: คุณไม่มีสิทธิ์เข้าถึงส่วนนี้'], 403);
    }
}
