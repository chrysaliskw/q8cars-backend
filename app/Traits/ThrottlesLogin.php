<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use App\Exceptions\UnprocessableEntityException;

trait ThrottlesLogin
{

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \App\Exceptions\UnprocessableEntityException
     */
    public function ensureIsNotRateLimited(Request $request)
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        $msg = trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]);

        throw new UnprocessableEntityException($msg);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->username()). '|' . request()->ip();
    }

    /**
     * Get the username key for auth
     * 
     * @return string
     */
    public function username()
    {
        return property_exists($this, 'username') ? $this->username : 'mobile';
    }
}
