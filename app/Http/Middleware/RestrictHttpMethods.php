<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictHttpMethods
{
    
    public function handle(Request $request, Closure $next): Response
    {
        $allowedMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD'];

        if (!in_array($request->method(), $allowedMethods)) {
            abort(405, 'Method Not Allowed');
        }

        return $next($request);
    }
}
