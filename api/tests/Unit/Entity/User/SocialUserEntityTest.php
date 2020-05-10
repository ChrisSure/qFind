<?php

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\SocialUser;
use App\Entity\User\User;
use PHPUnit\Framework\TestCase;
use Faker\Factory;

class SocialUserEntityTest extends TestCase
{
    private $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function testEntity(): void
    {
        $socialUser = new SocialUser();
        $socialUser->setProvider($provider = $socialUser::$PROVIDER_FACEBOOK);
        $socialUser->setName($name = $this->faker->name);
        $socialUser->setImage($image = $this->faker->title);
        $socialUser->setToken($token = $this->faker->title);
        $socialUser->setUser($user = $this->createMock(User::class));

        $this->assertEquals($provider, $socialUser->getProvider());
        $this->assertEquals($name, $socialUser->getName());
        $this->assertEquals($image, $socialUser->getImage());
        $this->assertEquals($token, $socialUser->getToken());

        $this->assertEquals(gettype($user), gettype($socialUser->getUser()));
    }
}