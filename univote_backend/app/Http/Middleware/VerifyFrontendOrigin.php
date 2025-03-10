<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyFrontendOrigin
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedOrigins = ['https://univote.nexelaris.com'];

        if (!in_array($request->header('Origin'), $allowedOrigins)) {
            return response()->json(['message' => 'Unauthorized request'], 403);
        }

        return $next($request);
    }
}
