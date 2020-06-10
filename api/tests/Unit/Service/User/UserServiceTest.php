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

    protected function setUp(): void
    {
        parent::setUp();
        $this->userMock = Mockery::mock(User::class);
        $this->userRepositoryMock = Mockery::mock(UserRepository::class);
        $this->serializeServiceMock = Mockery::mock(SerializeService::class);
        $this->passwordServiceMock = Mockery::mock(PasswordHashService::class);
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


}