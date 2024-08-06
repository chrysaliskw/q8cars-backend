<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
     /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException('Unauthenticated.', $guards, $this->redirectBasedOnGuard($request, $guards));
    }
    
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return string|null
     */
    protected function redirectBasedOnGuard($request, $guards)
    {
        if (! $request->expectsJson()) 
        {
            if (in_array('admin', $guards)) {
                return route('admin.login');
            }
            return '/';
        }
    }
}
