<?php

namespace App\Tests\Functional\Controller\User;

use App\Repository\User\UserRepository;
use App\Tests\Functional\Base;

class AllUserControllerTest extends Base
{
    /**
     * @test
     */
    public function all(): void
    {
        $token = $this->signInAdmin();
        //var_dump($token);exit();
        $this->client->request(
            'GET',
            '/users',
            [],
            [],
            ['Authorization' => "Bearer " . $token]
        );

        $this->client->followRedirect();
        $response = json_decode($this->client->getResponse()->getContent());
        $users = $response->users;
        $statusList = $response->statusList;
        $rolesList = $response->rolesList;

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_string($users));
        $this->assertTrue(isset($statusList));
        $this->assertTrue(isset($rolesList));
    }
}