<?php

namespace App\Tests\Unit\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\Auth\PasswordHashService;
use App\Service\Helper\SerializeService;
use App\Service\User\UserService;
use App\Tests\Unit\Base;
use Mockery;

class UserServiceTest extends Base
{
    private $userMock;

    private $userRepositoryMock;

    private $serializeServiceMock;

    private $passwordServiceMock;

    private $arrayData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userMock = Mockery::mock(User::class);
        $this->userRepositoryMock = Mockery::mock(UserRepository::class);
        $this->serializeServiceMock = Mockery::mock(SerializeService::class);
        $this->passwordServiceMock = Mockery::mock(PasswordHashService::class);
        $this->arrayData = ['email' => 'test@gmail.com', 'password' => 123, 'status' => User::$STATUS_NEW, 'role' => User::$ROLE_USER];
    }

    /**
     * @test
     */
    public function all()
    {
        $this->userRepositoryMock->shouldReceive('getAll')->andReturn([$this->userMock]);
        $this->serializeServiceMock->shouldReceive('serialize')->andReturn(json_encode($this->userMock));
        $userService = new UserService($this->userRepositoryMock, $this->serializeServiceMock, $this->passwordServiceMock);
        $result = $userService->all($this->faker->email, $this->faker->name, $this->faker->name, $this->faker->numberBetween(0, 100));

        $this->assertTrue(is_string($result));
    }

    /**
     * @test
     */
    public function totalUsers()
    {
        $this->userRepositoryMock->shouldReceive('getCountUsers')->andReturn($count = 9);
        $userService = new UserService($this->userRepositoryMock, $this->serializeServiceMock, $this->passwordServiceMock);
        $result = $userService->totalUsers($this->faker->email, $this->faker->name, $this->faker->name);

        $this->assertEquals($count, $result);
    }

    /**
     * @test
     */
    public function singleUser()
    {
        $this->userRepositoryMock->shouldReceive('get')->andReturn($this->userMock);
        $this->serializeServiceMock->shouldReceive('serialize')->andReturn(json_encode($this->userMock));
        $userService = new UserService($this->userRepositoryMock, $this->serializeServiceMock, $this->passwordServiceMock);
        $result = $userService->single(1);

        $this->assertTrue(is_string($result));
    }

    /**
     * @test
     */
    public function createUser()
    {
        $this->userRepositoryMock->shouldReceive('findOneBy')->andReturn(null);
        $this->userRepositoryMock->shouldReceive('save')->andReturn(null);
        $this->passwordServiceMock->shouldReceive('hashPassword')->andReturn($this->faker->sentence);
        $userService = new UserService($this->userRepositoryMock, $this->serializeServiceMock, $this->passwordServiceMock);
        $result = $userService->create($this->arrayData);

        $typeObject = false;
        if ($result instanceof User) {
            $typeObject = true;
        }

        $this->assertTrue($typeObject);
        $this->assertEquals($this->arrayData['email'], $result->getEmail());
    }

    /**
     * @test
     */
    public function alreadyIssetEmail(): void
    {
        $this->userRepositoryMock->shouldReceive('findOneBy')->andReturn($this->userMock);
        $this->userRepositoryMock->shouldReceive('save')->andReturn(null);
        $this->passwordServiceMock->shouldReceive('hashPassword')->andReturn($this->faker->sentence);

        $this->expectException(\InvalidArgumentException::class);

        $userService = new UserService($this->userRepositoryMock, $this->serializeServiceMock, $this->passwordServiceMock);
        $result = $userService->create($this->arrayData);
    }

    /**
     * @test
     */
    public function updateUser()
    {
        $this->userRepositoryMock->shouldReceive('get')->andReturn($this->userMock);
        $this->userMock->shouldReceive('setEmail')->andReturn($this->userMock);
        $this->userMock->shouldReceive('setPasswordHash')->andReturn($this->userMock);
        $this->userMock->shouldReceive('setRoles')->andReturn($this->userMock);
        $this->userMock->shouldReceive('setStatus')->andReturn($this->userMock);
        $this->userMock->shouldReceive('onPreUpdate')->andReturn($this->userMock);
        $this->userMock->shouldReceive('getEmail')->andReturn($this->arrayData['email']);
        $this->userRepositoryMock->shouldReceive('save')->andReturn(null);
        $this->passwordServiceMock->shouldReceive('hashPassword')->andReturn($this->faker->sentence);
        $userService = new UserService($this->userRepositoryMock, $this->serializeServiceMock, $this->passwordServiceMock);
        $result = $userService->update($this->arrayData, $this->faker->randomDigit);

        $typeObject = false;
        if ($result instanceof User) {
            $typeObject = true;
        }

        $this->assertTrue($typeObject);
        $this->assertEquals($this->arrayData['email'], $result->getEmail());
    }

    /**
     * @test
     */
    public function deleteUser()
    {
        $this->userRepositoryMock->shouldReceive('get')->andReturn($this->userMock);
        $this->userRepositoryMock->shouldReceive('delete')->andReturn(null);
        $userService = new UserService($this->userRepositoryMock, $this->serializeServiceMock, $this->passwordServiceMock);
        $result = $userService->delete($this->faker->randomDigit);

        $this->assertTrue(is_null($result));
    }

    /**
     * @test
     */
    public function activateUser()
    {
        $this->userRepositoryMock->shouldReceive('get')->andReturn($this->userMock);
        $this->userRepositoryMock->shouldReceive('save')->andReturn(null);
        $this->userMock->shouldReceive('setStatus')->andReturn($this->userMock);
        $this->userMock->shouldReceive('getStatus')->andReturn(User::$STATUS_ACTIVE);
        $userService = new UserService($this->userRepositoryMock, $this->serializeServiceMock, $this->passwordServiceMock);
        $result = $userService->activate($this->faker->randomDigit);

        $this->assertEquals(User::$STATUS_ACTIVE, $result->getStatus());
    }

    /**
     * @test
     */
    public function blockUser()
    {
        $this->userRepositoryMock->shouldReceive('get')->andReturn($this->userMock);
        $this->userRepositoryMock->shouldReceive('save')->andReturn(null);
        $this->userMock->shouldReceive('setStatus')->andReturn($this->userMock);
        $this->userMock->shouldReceive('getStatus')->andReturn(User::$STATUS_BLOCKED);
        $userService = new UserService($this->userRepositoryMock, $this->serializeServiceMock, $this->passwordServiceMock);
        $result = $userService->block($this->faker->randomDigit);

        $this->assertEquals(User::$STATUS_BLOCKED, $result->getStatus());
    }

}