<?php

namespace App\Tests\Unit\Validation\Auth;

use App\Tests\Unit\Base;
use App\Validation\Auth\UserAuthValidation;

class UserAuthValidationTest extends Base
{
    /**
     * @test
     */
    public function successValidate(): void
    {
        $validate = new UserAuthValidation();
        $data = ['email' => $this->faker->email, 'password' => $this->faker->password, 'type' => 'site'];
        $result = $validate->validate($data);

        $this->assertEquals(0, $result->count());
    }

    /**
     * @test
     */
    public function failureValidate(): void
    {
        $validate = new UserAuthValidation();
        $data = ['email' => $this->faker->title, 'password' => $this->faker->password, 'type' => 'site'];
        $result = $validate->validate($data);

        $this->assertEquals(1, $result->count());
    }
}