<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 1. 註冊全域中間件（每次 HTTP 請求都會先執行切換資料庫）
        $middleware->append(\App\Http\Middleware\SwitchDatabaseMiddleware::class);
// 這裡對應您原本在 Kernel.php 中的 $routeMiddleware
        $middleware->alias([
            'admin'      => \App\Http\Middleware\AdminMiddleware::class,
            'admin_exec' => \App\Http\Middleware\AdminExecMiddleware::class,
            'exec'       => \App\Http\Middleware\ExecMiddleware::class,
            'local'      => \App\Http\Middleware\LocalMiddleware::class,
        ]);        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
