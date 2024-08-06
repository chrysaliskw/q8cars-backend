<?php

namespace App\Http\Middleware\Api\User;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsNotGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->isNotGuest()) {
            return $next($request);
        }

        return response()->json([
            'status' => Response::HTTP_UNAUTHORIZED,
            'message' => 'Unauthenticated'
        ], Response::HTTP_UNAUTHORIZED);
        
    }
}
