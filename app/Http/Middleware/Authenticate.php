<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Session;

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
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    protected function oauth()
    {
        // $is_logged_in = Session::get('is_logged_in');
        $type = Session::get('type');

        // if (!$is_logged_in) {
        //     return route('login');
        // }

        if ($type === 'MEMBER') {
            return route('member');
        } else if ($type === 'EMPLOYEE' || $type === 'ADMIN') {
            return route('dashboard');
        }

        return route('login');
    }
}
