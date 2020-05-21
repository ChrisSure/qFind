<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{

    public function handle($request, Closure $next)
    {
        $flag = true;

        if (!$flag) {
            return redirect('/');
        }

        return $next($request);
    }
}
