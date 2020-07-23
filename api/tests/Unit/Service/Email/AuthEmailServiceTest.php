<?php

namespace App\Tests\Unit\Service\Email;

use App\Entity\User\User;
use App\Service\Email\AuthMailService;
use App\Tests\Unit\Base;
use Mockery;
use Swift_Mailer;
use Twig\Environment;

class AuthEmailServiceTest extends Base
{
    private $swiftMailerMock;

    private $twigEnvMock;

    private $userMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->swiftMailerMock = Mockery::mock(Swift_Mailer::class);
        $this->twigEnvMock = Mockery::mock(Environment::class);
        $this->userMock = Mockery::mock(User::class);
    }

    /**
     * @test
     */
    public function sendCheckRegistration(): void
    {
        $this->userMock->shouldReceive('getEmail')->andReturn($this->faker->email);
        $this->userMock->shouldReceive('getId')->andReturn($this->faker->randomDigit);
        $this->twigEnvMock->shouldReceive('render')->andReturn($this->faker->sentence);
        $this->twigEnvMock->shouldReceive('getTemplateClass')->andReturn($this->faker->sentence);
        $this->swiftMailerMock->shouldReceive('send')->andReturn(null);
        $authMailService = new AuthMailService($this->swiftMailerMock, $this->twigEnvMock);
        $result = $authMailService->sendCheckRegistration($this->userMock, $this->faker->sentence);

        $this->assertTrue(is_null($result));

    }
}