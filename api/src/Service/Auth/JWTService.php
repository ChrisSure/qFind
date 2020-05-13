<?php

namespace App\Service\Auth;

use App\Entity\User\User;
use \Firebase\JWT\JWT;

class JWTService
{
    private $key = "ny_find_key";

    public function decode(string $apiToken): object
    {
        return JWT::decode($apiToken, $this->key, array('HS256'));
    }

    public function create(User $user): string
    {
        $payload = array(
            "iss" => "http://nyfind.org",
            "aud" => "http://nyfind.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "email" => $user->getEmail(),
            "roles" => $user->getRoles(),
        );
        return JWT::encode($payload, $this->key);
    }
}