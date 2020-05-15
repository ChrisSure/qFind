<?php

namespace App\Service\Auth;

use App\Entity\User\User;
use \Firebase\JWT\JWT;

class JWTService
{
    private $secret = "ny_find_key";

    private $expire = 86400;

    /**
     * Decode jwt token
     *
     * @param string $apiToken
     * @return object
     */
    public function decode(string $apiToken): object
    {
        return JWT::decode($apiToken, $this->secret, array('HS256'));
    }

    /**
     * Encodes an array to a JWT and ensures iat and exp are set.
     *
     * @param array $data
     * @return string
     */
    public function encode(array $data): string
    {
        // Make sure an iat and exp is set
        if (!isset($data['iat'])) {
            $data['iat'] = time();
        }
        if (!isset($data['exp'])) {
            $data['exp'] = $data['iat'] + $this->expire;
        }

        return JWT::encode($data, $this->secret);
    }

    /**
     * Create user data for jwt token
     *
     * @param User $user
     * @return string
     */
    public function create(User $user): string
    {
        $data = array(
            "id" => $user->getId(),
            "email" => $user->getEmail(),
            "roles" => $user->getRoles(),
        );
        return $this->encode($data);
    }
}