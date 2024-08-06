<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * @var string
     */
    private $redirectRoute;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            switch ($guard) {
                case 'admin':
                    $this->redirectRoute = route('admin.dashboard');
                    break;

                case 'business_user':
                    $this->redirectRoute = route('business-user.dashboard');
                    break;
                    
                case 'web_user':
                    $this->redirectRoute = route('web-user.buisness-form.create');
                    break;

                default:
                    $this->redirectRoute = RouteServiceProvider::HOME;
            }

            if (Auth::guard($guard)->check()) {
                return redirect($this->redirectRoute);
            }
        }

        return $next($request);
    }
}
