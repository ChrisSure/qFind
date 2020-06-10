<?php

namespace Tests\Unit\Service\Auth;

use App\Service\Auth\TokenService;
use PHPUnit\Framework\TestCase;

class TokenServiceTest extends TestCase
{
    /**
     * @test
     */
    public function encode()
    {

        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MiwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJyb2xlcyI6WyJST0xFX0FETUlOIl0sImlhdCI6MTU5MTgxNTU1NywiZXhwIjoxNjA0MjAxOTU3fQ.DwtRD5NF5diaz8lazbuHmukcQocq3f6p5vNxoZrsLCw";
        $tokenService = new TokenService();
        $result = $tokenService->encode($token);

        $this->assertEquals($result->email, "admin@gmail.com");
        $this->assertEquals($result->roles[0], "ROLE_ADMIN");
    }
}
