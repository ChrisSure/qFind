<?php

namespace App\Tests\Unit\Validation\User;

use App\Entity\User\User;
use App\Tests\Unit\Base;
use App\Validation\Auth\CreateUserValidation;

class CreateUserValidationTest extends Base
{
    /**
     * @test
     */
    public function successValidate(): void
    {
        $validate = new CreateUserValidation();
        $data = ['email' => $this->faker->email, 'password' => $this->faker->password, 'role' => User::$ROLE_USER, 'status' => User::$STATUS_NEW];
        $result = $validate->validate($data);

        $this->assertEquals(0, $result->count());
    }

    /**
     * @test
     */
    public function failureValidate(): void
    {
        $validate = new CreateUserValidation();
        $data = ['email' => $this->faker->title, 'password' => $this->faker->password];
        $result = $validate->validate($data);

        $this->assertEquals(3, $result->count());
    }
}