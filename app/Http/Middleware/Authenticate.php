<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {

        // if (Auth::guard($guard)->guest()) {
        //     if ($guard === 'api') {
        //         return response('Unauthorized.', 401);
        //     } else {
        //         return redirect()->guest('login');
        //     }
        // }
        // return $next($request);
    }

}
