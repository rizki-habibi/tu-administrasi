<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'role:admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web', 'auth', 'role:all_staff'])
                ->prefix('staf')
                ->name('staf.')
                ->group(base_path('routes/staf.php'));

            Route::middleware(['web', 'auth', 'role:kepala_sekolah'])
                ->prefix('kepala-sekolah')
                ->name('kepala-sekolah.')
                ->group(base_path('routes/kepala-sekolah.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
