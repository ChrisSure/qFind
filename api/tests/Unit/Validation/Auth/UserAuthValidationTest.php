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

    /**
     * @test
     */
    public function successValidate(): void
    {
        $validate = new UserAuthValidation();
        $data = ['email' => $this->faker->email, 'password' => $this->faker->password];
        $result = $validate->validate($data);

        $this->assertEquals(0, $result->count());
    }

    /**
     * @test
     */
    public function failureValidate(): void
    {
        $validate = new UserAuthValidation();
        $data = ['email' => $this->faker->title, 'password' => $this->faker->password];
        $result = $validate->validate($data);

        $this->assertEquals(1, $result->count());
    }
}