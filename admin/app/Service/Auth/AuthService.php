<?php

namespace App\Service\Auth;

class AuthService
{

    public function setToken(string $token, $remember): void
    {
        ($remember) ? $this->setCoockieToken() : $this->setSessionToken();
    }

    public function setCoockieToken()
    {

    }

    public function setSessionToken()
    {

    }

}
