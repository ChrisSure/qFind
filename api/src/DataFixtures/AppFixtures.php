<?php

namespace App\DataFixtures;

use App\Entity\User\SocialUser;
use App\Entity\User\User;
use App\Service\Auth\PasswordHashService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * @var PasswordHashService
     */
    private $passwordHashService;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * AppFixtures constructor.
     *
     * @param PasswordHashService $passwordHashService
     */
    public function __construct(PasswordHashService $passwordHashService)
    {
        $this->passwordHashService = $passwordHashService;
        $this->faker = Factory::create();
    }

    /**
     * Load fixture
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // Set User
        $user = new User();
        $user->setEmail("user@gmail.com");
        $user->setRoles([$user::$ROLE_USER]);
        $user->setPasswordHash($this->passwordHashService->hashPassword($user, '123'));
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


        // Set Admin
        $admin = new User();
        $admin->setEmail("admin@gmail.com");
        $admin->setRoles([$user::$ROLE_ADMIN]);
        $admin->setPasswordHash($this->passwordHashService->hashPassword($user, '123'));
        $admin->setStatus($user::$STATUS_ACTIVE);
        $admin->onPrePersist();
        $admin->onPreUpdate();

        $manager->persist($admin);


        // Set Super admin
        $superAdmin = new User();
        $superAdmin->setEmail("super_admin@gmail.com");
        $superAdmin->setRoles([$user::$ROLE_SUPER_ADMIN]);
        $superAdmin->setPasswordHash($this->passwordHashService->hashPassword($user, '123'));
        $superAdmin->setStatus($user::$STATUS_ACTIVE);
        $superAdmin->onPrePersist();
        $superAdmin->onPreUpdate();

        $manager->persist($superAdmin);


        $manager->flush();
    }
}
