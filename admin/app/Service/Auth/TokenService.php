<?php

namespace App\Service\Auth;

use \Firebase\JWT\JWT;

class TokenService
{
    public function encode($token): object
    {
        $key = env('JWT_SECRET', null);
        return JWT::decode($token, $key, array('HS256'));
    }
}
