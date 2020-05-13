<?php

namespace App\Tests\Unit\Validation\Auth;

use App\Validation\Auth\UserAuthValidation;
use PHPUnit\Framework\TestCase;
use Faker\Factory;

class UserAuthValidationTest extends TestCase
{
    private $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function testSuccessValidate(): void
    {
        $validate = new UserAuthValidation();
        $data = ['email' => $this->faker->email, 'password' => $this->faker->password];
        $result = $validate->validate($data);

        $this->assertEquals(0, $result->count());
    }

    public function testFailureValidate(): void
    {
        $validate = new UserAuthValidation();
        $data = ['email' => $this->faker->title, 'password' => $this->faker->password];
        $result = $validate->validate($data);

        $this->assertEquals(1, $result->count());
    }
}