<?php

namespace App\Service\Auth;

use \Firebase\JWT\JWT;

class JWTService
{
    private $key = "ny_find_key";

    public function decode(string $apiToken): object
    {
        return JWT::decode($apiToken, $this->key, array('HS256'));
    }

    public function create(): string
    {
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000
        );
        return JWT::encode($payload, $this->key);
    }
}