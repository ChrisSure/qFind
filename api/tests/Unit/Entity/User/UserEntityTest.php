<?php

namespace App\Tests\Unit\Entity\User;

use App\Entity\User\User;
use App\Tests\Unit\Base;
use Doctrine\Common\Collections\ArrayCollection;

class UserEntityTest extends Base
{
    /**
     * @test
     */
    public function checkEntity(): void
    {
        $user = new User();
        $user->setEmail($email = $this->faker->email);
        $user->setRoles($role = User::$ROLE_USER);
        $user->setPasswordHash($password = $this->faker->password);
        $user->setStatus($status = $user::$STATUS_ACTIVE);

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($role, $user->getRoles());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($status, $user->getStatus());

        $this->assertTrue($user->getSocial() instanceof ArrayCollection);
    }
}