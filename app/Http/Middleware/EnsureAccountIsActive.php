<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && !$user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your account has been deactivated. Contact the administrator.'], 403);
            }
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been deactivated. Contact the administrator.',
            ]);
        }
        return $next($request);
    }
}
