<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        $plain = $request->bearerToken();
        $token = $plain
            ? ApiToken::query()->with('user')->where('token', hash('sha256', $plain))->first()
            : null;

        if (! $token?->user || $token->user->role === 'admin') {
            return response()->json(['message' => 'Sign in to continue.'], 401);
        }

        $token->forceFill(['last_used_at' => now()])->save();
        $request->setUserResolver(fn () => $token->user);

        return $next($request);
    }
};
