<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class DenyBlockedUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestedRouteName = $request->route()->getName(); //blocked, profile.index 
        $isBlocked = Auth::user()->is_blocked;  
        if (!$isBlocked && $requestedRouteName == 'blocked'){
            return redirect()->route('profile.index');
        }
        else if ($isBlocked){
            return redirect()->route('blocked');
        }
        return $next($request);
    }
}
