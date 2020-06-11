<?php

namespace App\Service\Auth;

use App\Facades\Auth\User;

class AuthService
{
    private $tokenService;

    private $minutes = 6 * 24 * 30;

    private $siteName;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
        $this->siteName = env('APP_NAME', null);
    }

    public function setToken(string $token): void
    {
        $tokenData = $this->tokenService->encode($token);
        User::setUserData($token, $tokenData);
    }

    public function isAuth()
    {
        return User::isAuth();
    }

    public function logout()
    {
        User::removeData();
    }

}
