<?php

namespace App\Tests\Unit\Service\Auth;

use App\Entity\User\User;
use App\Entity\User\UserToken;
use App\Repository\User\UserRepository;
use App\Service\Auth\AuthService;
use App\Service\Auth\JWTService;
use App\Service\Auth\PasswordHashService;
use App\Service\Email\AuthMailService;
use App\Service\Helper\SerializeService;
use App\Service\User\UserTokenService;
use App\Tests\Unit\Base;
use Mockery;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthServiceTest extends Base
{
    private $userMock;

    private $userTokenMock;

    private $userRepositoryMock;

    private $passwordHashServiceMock;

    private $jwtService;

    private $userTokenService;

    private $serializeService;

    private $authMailService;


    protected function setUp(): void
    {
        parent::setUp();
        $this->userMock = Mockery::mock(User::class);
        $this->userTokenMock = Mockery::mock(UserToken::class);
        $this->userRepositoryMock = Mockery::mock(UserRepository::class);
        $this->passwordHashServiceMock = Mockery::mock(PasswordHashService::class);
        $this->jwtService = Mockery::mock(JWTService::class);
        $this->userTokenService = Mockery::mock(UserTokenService::class);
        $this->serializeService = Mockery::mock(SerializeService::class);
        $this->authMailService = Mockery::mock(AuthMailService::class);
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

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );
        $result = $authService->loginUser(['email' => $this->faker->email, 'password' => $this->faker->password, 'type' => 'site']);

        $this->assertTrue(is_string($result));
    }

    /**
     * @test
     */
    public function loginAdminAsAdmin(): void
    {
        $this->userMock->shouldReceive('getStatus')->andReturn(User::$STATUS_ACTIVE);
        $this->userMock->shouldReceive('getRoles')->andReturn([User::$ROLE_ADMIN]);
        $this->userRepositoryMock->shouldReceive('findOneBy')->andReturn($this->userMock);
        $this->passwordHashServiceMock->shouldReceive('checkPassword')->andReturn(true);
        $this->jwtService->shouldReceive('create')->andReturn($this->faker->name);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );
        $result = $authService->loginUser(['email' => $this->faker->email, 'password' => $this->faker->password, 'type' => 'admin']);

        $this->assertTrue(is_string($result));
    }

    /**
     * @test
     */
    public function loginAdminAsSuperAdmin(): void
    {
        $this->userMock->shouldReceive('getStatus')->andReturn(User::$STATUS_ACTIVE);
        $this->userMock->shouldReceive('getRoles')->andReturn([User::$ROLE_SUPER_ADMIN]);
        $this->userRepositoryMock->shouldReceive('findOneBy')->andReturn($this->userMock);
        $this->passwordHashServiceMock->shouldReceive('checkPassword')->andReturn(true);
        $this->jwtService->shouldReceive('create')->andReturn($this->faker->name);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );
        $result = $authService->loginUser(['email' => $this->faker->email, 'password' => $this->faker->password, 'type' => 'admin']);

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

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );
        $result = $authService->loginUser(['email' => $this->faker->email, 'password' => $this->faker->password, 'type' => 'site']);
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

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );
        $result = $authService->loginUser(['email' => $this->faker->email, 'password' => $this->faker->password, 'type' => 'site']);
    }

    /**
     * @test
     */
    public function uncorrectRoleNotAdmin(): void
    {
        $this->userMock->shouldReceive('getStatus')->andReturn(User::$STATUS_ACTIVE);
        $this->userMock->shouldReceive('getRoles')->andReturn([User::$ROLE_USER]);
        $this->userRepositoryMock->shouldReceive('findOneBy')->andReturn($this->userMock);
        $this->passwordHashServiceMock->shouldReceive('checkPassword')->andReturn(true);
        $this->jwtService->shouldReceive('create')->andReturn($this->faker->name);

        $this->expectException(AccessDeniedHttpException::class);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );
        $result = $authService->loginUser(['email' => $this->faker->email, 'password' => $this->faker->password, 'type' => 'admin']);
    }

    /**
     * @test
     */
    public function alreadyIssetEmail(): void
    {
        $this->userMock->shouldReceive('getStatus')->andReturn(User::$STATUS_BLOCKED);
        $this->userRepositoryMock->shouldReceive('findOneBy')->andReturn($this->userMock);
        $this->passwordHashServiceMock->shouldReceive('checkPassword')->andReturn(true);
        $this->jwtService->shouldReceive('create')->andReturn($this->faker->name);

        $this->expectException(\InvalidArgumentException::class);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );
        $result = $authService->createUser(['email' => $this->faker->email, 'password' => $this->faker->password, 'type' => 'site']);
    }

    /**
     * @test
     */
    public function createUserSuccessfull(): void
    {
        $this->userMock->shouldReceive('getStatus')->andReturn(User::$STATUS_BLOCKED);
        $this->userRepositoryMock->shouldReceive('findOneBy')->andReturn(null);
        $this->userRepositoryMock->shouldReceive('save')->andReturn(null);
        $this->passwordHashServiceMock->shouldReceive('checkPassword')->andReturn(true);
        $this->passwordHashServiceMock->shouldReceive('hashPassword')->andReturn($this->faker->name);
        $this->jwtService->shouldReceive('create')->andReturn($this->faker->name);
        $this->userTokenService->shouldReceive('generateToken')->andReturn($this->userTokenMock);
        $this->serializeService->shouldReceive('serialize')->andReturn($this->faker->name);
        $this->userTokenMock->shouldReceive('getToken')->andReturn($this->faker->name);
        $this->authMailService->shouldReceive('sendCheckRegistration')->andReturn(null);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );
        $result = $authService->createUser(['email' => $email = $this->faker->email, 'password' => $this->faker->password, 'type' => 'site']);

        $this->assertTrue(is_object($result));
        $this->assertEquals($result->getEmail(), $email);
    }

}