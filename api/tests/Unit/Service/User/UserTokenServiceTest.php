<?php

namespace App\Tests\Unit\Service\User;

use App\Entity\User\UserToken;
use App\Service\User\UserTokenService;
use App\Tests\Unit\Base;

class UserTokenServiceTest extends Base
{
    /**
     * @test
     */
    public function generateToken(): void
    {
        $userTokenService = new UserTokenService();
        $result = $userTokenService->generateToken();

        $typeObject = false;
        if ($result instanceof UserToken) {
            $typeObject = true;
        }

        $this->assertTrue($typeObject);
    }
}