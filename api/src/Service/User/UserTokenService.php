<?php

namespace App\Service\User;

use App\Entity\User\UserToken;

class UserTokenService
{
    /**
     * Generate string token
     * @return UserToken
     */
    public function generateToken(): UserToken
    {
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $expired = time() + (60 * 15);
        return new UserToken($token, $expired);
    }
}