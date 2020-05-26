<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Cookie;

class Authenticate extends Middleware
{

    public function handle($request, Closure $next)
    {
        $flag = $this->getState();

        if (!$flag) {
            return redirect('/');
        }

        return $next($request);
    }

    private function getState(): ?string
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_jwt_token') !== null)
            ? session($siteName . '_jwt_token')
            : Cookie::get($siteName . '_jwt_token');
    }
}
