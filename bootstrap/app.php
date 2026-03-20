<?php

use App\Http\Middleware\CheckUserActive;
use App\Http\Middleware\EnsureUserSubscribed;
use App\Http\Middleware\OwnerMiddleware;
use App\Http\Middleware\TenantMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Exclude Stripe Webhooks from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'stripe/*', // Using wildcard covers both /stripe/webhook and other cashier routes
        ]);

        $middleware->alias([
            'subscription' => EnsureUserSubscribed::class,
            'owner' => OwnerMiddleware::class,
            'tenant' => TenantMiddleware::class,
            'active' => CheckUserActive::class,

            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
