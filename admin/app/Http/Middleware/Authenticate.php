<?php

namespace App\Http\Middleware;

use App\Facades\Auth\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{

    public function handle($request, Closure $next)
    {
        $flag = User::isAuth();

        if (!$flag) {
            return redirect('/');
        }

        return $next($request);
    }

}
