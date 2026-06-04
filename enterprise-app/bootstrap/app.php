<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckAdminOrHr; // นำเข้าไฟล์ Middleware ที่เราสร้าง

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ลงทะเบียนชื่อเล่น 'admin_hr' ตรงนี้ครับ
        $middleware->alias([
            'admin_hr'        => CheckAdminOrHr::class,
            'permission'      => \App\Http\Middleware\CheckPermission::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeout::class,
        ]);

        // Ensure session.timeout runs before auth:sanctum so it can check
        // last_used_at before Sanctum updates it on each request
        $middleware->prependToPriorityList(
            \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
            \App\Http\Middleware\SessionTimeout::class,
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();