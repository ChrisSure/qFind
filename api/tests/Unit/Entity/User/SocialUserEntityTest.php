<?php

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\SocialUser;
use App\Entity\User\User;
use App\Tests\Unit\Base;

class SocialUserEntityTest extends Base
{
    /**
     * @test
     */
    public function checkEntity(): void
    {
        $socialUser = new SocialUser();
        $socialUser->setProvider($provider = $socialUser::$PROVIDER_FACEBOOK);
        $socialUser->setName($name = $this->faker->name);
        $socialUser->setImage($image = $this->faker->title);
        $socialUser->setAppId($appId = $this->faker->title);
        $socialUser->setUser($user = $this->createMock(User::class));

        $this->assertEquals($provider, $socialUser->getProvider());
        $this->assertEquals($name, $socialUser->getName());
        $this->assertEquals($image, $socialUser->getImage());
        $this->assertEquals($appId, $socialUser->getAppId());

        $this->assertEquals(gettype($user), gettype($socialUser->getUser()));
    }
}