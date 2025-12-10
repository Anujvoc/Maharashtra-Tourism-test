<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{

    public function handle(Request $request, Closure $next, $is_role): Response
    {
        if($request->user()->role !== $is_role){
            if($request->user()->role == 'vendor'){
                return redirect()->route('vendor.dashbaord');
            }elseif ($request->user()->role == 'admin'){
                return redirect()->route('admin.dashbaord');
            }else {
                return redirect()->route('frontend.dashboard');
            }
        }
        return $next($request);
    }

}
