<?php

use App\Services\PortalAuthenticator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Railway terminates TLS at its edge and forwards plain HTTP with
        // X-Forwarded-* headers, so trust the proxy to get correct https URLs.
        $middleware->trustProxies(at: '*');

        // Admins, riders and clients all sign in at /login, so every guard sends
        // its guests to the same door.
        $middleware->redirectGuestsTo(fn () => route('login'));

        // Already signed in? Go to whichever dashboard the active guard owns.
        $middleware->redirectUsersTo(fn () => app(PortalAuthenticator::class)->homeForCurrentUser());
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Resource not found.',
                ], 404);
            }
        });
    })->create();
