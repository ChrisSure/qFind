<?php

namespace App\Tests\Unit\Validation\Auth;

use App\Tests\Unit\Base;
use App\Validation\Auth\NewPasswordValidation;

class NewPasswordValidationTest extends Base
{
    /**
     * @test
     */
    public function successValidate(): void
    {
        $validate = new NewPasswordValidation();
        $data = ['password' => $this->faker->password];
        $result = $validate->validate($data);

        $this->assertEquals(0, $result->count());
    }

    /**
     * @test
     */
    public function failureValidate(): void
    {
        $validate = new NewPasswordValidation();
        $data = ['password' => ''];
        $result = $validate->validate($data);

        $this->assertEquals(2, $result->count());
    }
}