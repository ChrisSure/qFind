<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Faker\Factory;

class Base extends TestCase
{
    protected $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }
}