<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('sanctum')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token không hợp lệ hoặc đã hết hạn',
                'code' => 401,
                'data' => null,
            ], 401);
        }

        return $next($request);
    }
}
