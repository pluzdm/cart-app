<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Random\RandomException;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuestCartToken
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws RandomException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cookieName = 'guest_cart_token';

        if (!$request->hasCookie($cookieName)) {
            $token = bin2hex(random_bytes(16));
            cookie()->queue(cookie($cookieName, $token, 60 * 24 * 30));
        }

        return $next($request);
    }
}
