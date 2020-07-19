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

    // Login user
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
    // Login user

    // Register user
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
    // Register user

    // Confirm User
    /**
     * @test
     */
    public function confirmRegisterUser(): void
    {
        $token = $this->faker->sentence;
        $userTokenObject = new UserToken($token, 4102437600);

        $this->userRepositoryMock->shouldReceive('get')->andReturn($this->userMock);
        $this->userRepositoryMock->shouldReceive('save')->andReturn(null);
        $this->userMock->shouldReceive('getToken')->andReturn($this->faker->sentence);
        $this->userMock->shouldReceive('setStatus')->andReturn($this->userMock);
        $this->userMock->shouldReceive('setToken')->andReturn($this->userMock);
        $this->userMock->shouldReceive('onPreUpdate')->andReturn($this->userMock);
        $this->serializeService->shouldReceive('deserialize')->andReturn($userTokenObject);
        $this->jwtService->shouldReceive('create')->andReturn($jwtToken = $this->faker->name);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );

        $result = $authService->confirmRegisterUser(['id' => $this->faker->randomDigit, 'token' => $token]);

        $this->assertEquals($result, $jwtToken);
    }
    // Confirm User

    // Forgot password
    /**
     * @test
     */
    public function expiredTokenForgotPassword(): void
    {
        $token = $this->faker->sentence;
        $userTokenObject = new UserToken($token, 1102437600);

        $this->userRepositoryMock->shouldReceive('getByEmail')->andReturn($this->userMock);
        $this->userMock->shouldReceive('getToken')->andReturn($token);
        $this->serializeService->shouldReceive('deserialize')->andReturn($userTokenObject);

        $this->expectException(\BadMethodCallException::class);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );

        $result = $authService->forgetPassword(['email' => $this->faker->email]);
    }

    /**
     * @test
     */
    public function forgotPassword(): void
    {
        $email = $this->faker->email;
        $token = $this->faker->sentence;
        $userTokenObject = new UserToken($token, 102437600);

        $this->userRepositoryMock->shouldReceive('getByEmail')->andReturn($this->userMock);
        $this->userRepositoryMock->shouldReceive('save')->andReturn(null);
        $this->userMock->shouldReceive('getToken')->andReturn($token);
        $this->userMock->shouldReceive('getEmail')->andReturn($email);
        $this->userMock->shouldReceive('setToken')->andReturn($this->userMock);
        $this->userMock->shouldReceive('onPreUpdate')->andReturn($this->userMock);
        $this->serializeService->shouldReceive('deserialize')->andReturn($userTokenObject);
        $this->userTokenService->shouldReceive('generateToken')->andReturn($this->userTokenMock);
        $this->serializeService->shouldReceive('serialize')->andReturn($this->faker->name);
        $this->userTokenMock->shouldReceive('getToken')->andReturn($this->faker->name);
        $this->authMailService->shouldReceive('sendForgetPassword')->andReturn(null);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );

        $result = $authService->forgetPassword(['email' => $email]);

        $this->assertTrue(is_object($result));
        $this->assertEquals($result->getEmail(), $email);
    }
    // Forgot password

    // Confirm new password
    /**
     * @test
     */
    public function confirmNewPassword(): void
    {
        $token = $this->faker->sentence;
        $userTokenObject = new UserToken($token, 11112437600);

        $this->userRepositoryMock->shouldReceive('get')->andReturn($this->userMock);
        $this->userMock->shouldReceive('getToken')->andReturn($token);
        $this->serializeService->shouldReceive('deserialize')->andReturn($userTokenObject);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );

        $result = $authService->confirmNewPassword(['user_id' => $this->faker->randomDigit, 'token' => $token]);

        $this->assertTrue(is_object($result));
        $this->assertEquals($result->getToken(), $token);
    }
    // Confirm new password

    // Set new password
    /**
     * @test
     */
    public function newPassword(): void
    {
        $token = $this->faker->sentence;
        $userTokenObject = new UserToken($token, 11112437600);

        $this->userRepositoryMock->shouldReceive('get')->andReturn($this->userMock);
        $this->userRepositoryMock->shouldReceive('save')->andReturn(null);
        $this->passwordHashServiceMock->shouldReceive('hashPassword')->andReturn($this->faker->sentence);
        $this->userMock->shouldReceive('getToken')->andReturn($token);
        $this->userMock->shouldReceive('setToken')->andReturn($this->userMock);
        $this->userMock->shouldReceive('setPasswordHash')->andReturn($this->userMock);
        $this->userMock->shouldReceive('onPreUpdate')->andReturn($this->userMock);
        $this->jwtService->shouldReceive('create')->andReturn($jwtToken = $this->faker->name);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );

        $result = $authService->newPassword(['password' => $this->faker->sentence], $this->faker->randomDigit);

        $this->assertEquals($result, $jwtToken);
    }
    // Set new password

    //Separate problems
    /**
     * @test
     */
    public function userHaveNotToken(): void
    {
        $user = new User();
        $user->setEmail($this->faker->email);
        $user->setStatus(User::$STATUS_NEW);
        $user->setRoles(User::$ROLE_USER);
        $user->setPasswordHash($this->faker->password);
        $this->userRepositoryMock->shouldReceive('get')->andReturn($user);

        $this->expectException(NotFoundHttpException::class);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );

        $result = $authService->confirmRegisterUser(['id' => $this->faker->randomDigit, 'token' => $this->faker->sentence]);
    }

    /**
     * @test
     */
    public function haveMissedData(): void
    {
        $token = $this->faker->sentence;
        $userTokenObject = new UserToken($token, 4102437600);

        $this->userRepositoryMock->shouldReceive('get')->andReturn($this->userMock);
        $this->userMock->shouldReceive('getToken')->andReturn($this->faker->sentence);
        $this->serializeService->shouldReceive('deserialize')->andReturn($userTokenObject);

        $this->expectException(\BadMethodCallException::class);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );

        $result = $authService->confirmRegisterUser(['id' => $this->faker->randomDigit, 'token' => $this->faker->sentence]);
    }

    /**
     * @test
     */
    public function expiredToken(): void
    {
        $token = $this->faker->sentence;
        $userTokenObject = new UserToken($token, 1102437600);

        $this->userRepositoryMock->shouldReceive('get')->andReturn($this->userMock);
        $this->userMock->shouldReceive('getToken')->andReturn($this->faker->sentence);
        $this->serializeService->shouldReceive('deserialize')->andReturn($userTokenObject);

        $this->expectException(\InvalidArgumentException::class);

        $authService = new AuthService(
            $this->userRepositoryMock,
            $this->passwordHashServiceMock,
            $this->jwtService,
            $this->userTokenService,
            $this->serializeService,
            $this->authMailService
        );

        $result = $authService->confirmRegisterUser(['id' => $this->faker->randomDigit, 'token' => $token]);
    }
    //Separate problems

}