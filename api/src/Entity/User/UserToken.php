<?php

namespace App\Entity\User;

class UserToken
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     */
    private $expired;

    /**
     * UserToken constructor.
     * @param $token
     * @param $expired
     */
    public function __construct($token, $expired)
    {
        $this->token = $token;
        $this->expired = $expired;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getExpired(): int
    {
        return $this->expired;
    }
}