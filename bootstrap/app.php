<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        // Redirigir en vez de mostrar 403 cuando el usuario no tiene el rol requerido
        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autorizado.'], 403);
            }

            if (auth()->check()) {
                $user = auth()->user();
                if ($user->hasRole('patient')) {
                    return redirect()->route('patient.dashboard')
                        ->with('error', 'No tienes permisos para acceder a esa sección.');
                }
                if ($user->hasRole('admin') || $user->hasRole('staff')) {
                    return redirect()->route('admin.dashboard')
                        ->with('error', 'No tienes permisos para acceder a esa sección.');
                }
            }

            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión primero.');
        });
    })->create();
