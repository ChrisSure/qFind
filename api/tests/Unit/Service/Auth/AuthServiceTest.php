<?php

namespace App\Tests\Unit\Service\Auth;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\Auth\AuthService;
use App\Service\Auth\JWTService;
use App\Service\Auth\PasswordHashService;
use PHPUnit\Framework\TestCase;
use Faker\Factory;
use Mockery;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthServiceTest extends TestCase
{
    private $faker;

    private $userMock;

    private $userRepositoryMock;

    private $passwordHashServiceMock;

    private $jwtService;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
        $this->userMock = Mockery::mock(User::class);
        $this->userRepositoryMock = Mockery::mock(UserRepository::class);
        $this->passwordHashServiceMock = Mockery::mock(PasswordHashService::class);
        $this->jwtService = Mockery::mock(JWTService::class);
    }

    /**
     * @test
     */
    public function loginUser(): void
    {
        $this->userMock->shouldReceive('getStatus')->andReturn(User::$STATUS_ACTIVE);
        $this->userRepositoryMock->shouldReceive('findOneBy')->andReturn($this->userMock);
        $this->passwordHashServiceMock->shouldReceive('checkPassword')->andReturn(true);
        $this->jwtService->shouldReceive('create')->andReturn($this->faker->name);

        $authService = new AuthService($this->userRepositoryMock, $this->passwordHashServiceMock, $this->jwtService);
        $result = $authService->loginUser(['email' => $this->faker->email, 'password' => $this->faker->password]);

        $this->assertTrue(is_string($result));
    }

    /**
     * @test
     */
    public function uncorrectPassword(): void
    {
        $this->userMock->shouldReceive('getStatus')->andReturn(User::$STATUS_ACTIVE);
        $this->userRepositoryMock->shouldReceive('findOneBy')->andReturn($this->userMock);
        $this->passwordHashServiceMock->shouldReceive('checkPassword')->andReturn(false);
        $this->jwtService->shouldReceive('create')->andReturn($this->faker->name);

        $this->expectException(NotFoundHttpException::class);

        $authService = new AuthService($this->userRepositoryMock, $this->passwordHashServiceMock, $this->jwtService);
        $result = $authService->loginUser(['email' => $this->faker->email, 'password' => $this->faker->password]);
    }

    /**
     * @test
     */
    public function uncorrectStatus(): void
    {
        $this->userMock->shouldReceive('getStatus')->andReturn(User::$STATUS_BLOCKED);
        $this->userRepositoryMock->shouldReceive('findOneBy')->andReturn($this->userMock);
        $this->passwordHashServiceMock->shouldReceive('checkPassword')->andReturn(true);
        $this->jwtService->shouldReceive('create')->andReturn($this->faker->name);

        $this->expectException(AccessDeniedHttpException::class);

        $authService = new AuthService($this->userRepositoryMock, $this->passwordHashServiceMock, $this->jwtService);
        $result = $authService->loginUser(['email' => $this->faker->email, 'password' => $this->faker->password]);
    }
}