<?php

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Faker\Factory;

class UserEntityTest extends TestCase
{
    private $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    /**
     * @test
     */
    public function checkEntity(): void
    {
        $user = new User();
        $user->setEmail($email = $this->faker->email);
        $user->setRoles($role = ["ROLE_USER"]);
        $user->setPasswordHash($password = $this->faker->password);
        $user->setStatus($status = $user::$STATUS_ACTIVE);

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($role, $user->getRoles());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($status, $user->getStatus());

        $this->assertTrue($user->getSocial() instanceof ArrayCollection);
    }
}