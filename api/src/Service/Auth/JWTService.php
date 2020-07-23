<?php

namespace App\Service\Auth;

use App\Entity\User\User;
use \Firebase\JWT\JWT;

class JWTService
{
    /**
     * @var string $jwtSecret
     */
    private $jwtSecret;

    /**
     * @var int $jwtExpire
     */
    private $jwtExpire;

    /**
     * JWTService constructor.
     *
     * @param $jwtSecret
     * @param $jwtExpire
     */
    public function __construct($jwtSecret, $jwtExpire)
    {
        $this->jwtSecret = $jwtSecret;
        $this->jwtExpire = $jwtExpire;
    }

    /**
     * Decode jwt token
     *
     * @param string $apiToken
     * @return object
     */
    public function decode(string $apiToken): object
    {
        return JWT::decode($apiToken, $this->jwtSecret, array('HS256'));
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
            $data['exp'] = $data['iat'] + $this->jwtExpire;
        }

        return JWT::encode($data, $this->jwtSecret);
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