<?php

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\UserToken;
use App\Tests\Unit\Base;

class UserTokenEntityTest extends Base
{
    /**
     * @test
     */
    public function checkEntity(): void
    {
        $userToken = new UserToken($token = $this->faker->sentence, $expired = time());

        $this->assertEquals($token, $userToken->getToken());
        $this->assertEquals($expired, $userToken->getExpired());
    }

    /**
     * @test
     */
    public function checkIsExpired(): void
    {
        $userToken = new UserToken($token = $this->faker->sentence, $expired = time() - 60 * 15);

        $this->assertTrue($userToken->isExpiredToken());
    }
}