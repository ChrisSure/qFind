<?php

namespace App\DataFixtures;

use App\Entity\User\SocialUser;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $encoder;

    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail($this->faker->email);
        $user->setPasswordHash($this->encoder->encodePassword($user, '123'));
        $user->setStatus($user::$STATUS_ACTIVE);
        $user->onPrePersist();
        $user->onPreUpdate();

        $socialUser = new SocialUser();
        $socialUser->setProvider($socialUser::$PROVIDER_FACEBOOK);
        $socialUser->setName($this->faker->name);
        $socialUser->setImage($this->faker->image());
        $socialUser->setToken($this->faker->sha256);

        $socialUser->setUser($user);

        $manager->persist($user);
        $manager->persist($socialUser);
        $manager->flush();
    }
}
