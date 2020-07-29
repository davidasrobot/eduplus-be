<?php

namespace App\Http\Middleware;

use Closure;

class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($guard != null){
            auth()->shouldUse($guard);
            if(auth()->user()->active !== 1){
                return response()->json([
                    'errors' => 'user banned.'
                ], 401);
            }
        }
        return $next($request);
    }
}
