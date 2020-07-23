<?php

namespace App\Tests\Unit\Validation\Auth;

use App\Tests\Unit\Base;
use App\Validation\Auth\ForgetPasswordValidation;

class ForgetPasswordValidationTest extends Base
{
    /**
     * @test
     */
    public function successValidate(): void
    {
        $validate = new ForgetPasswordValidation();
        $data = ['email' => $this->faker->email];
        $result = $validate->validate($data);

        $this->assertEquals(0, $result->count());
    }

    /**
     * @test
     */
    public function failureValidate(): void
    {
        $validate = new ForgetPasswordValidation();
        $data = ['email' => $this->faker->title];
        $result = $validate->validate($data);

        $this->assertEquals(1, $result->count());
    }
}