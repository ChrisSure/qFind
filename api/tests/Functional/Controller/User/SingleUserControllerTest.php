<?php

namespace App\Tests\Functional\Controller\User;

use App\Entity\User\User;
use App\Tests\Functional\Base;

class SingleUserControllerTest extends Base
{
    /**
     * @test
     */
    public function singleSuccess(): void
    {
        $this->signIn(User::$ROLE_ADMIN);

        $this->client->request('GET', '/users/' . $id = 1);

        $response = json_decode($this->client->getResponse()->getContent());
        $user = json_decode($response->user);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($user->id, $id);

    }

    /**
     * @test
     */
    public function singleNotFound(): void
    {
        $this->signIn(User::$ROLE_ADMIN);

        $this->client->request('GET', '/users/' . $id = 19999);

        $response = json_decode($this->client->getResponse()->getContent());
        $error = $response->error;

        $this->assertTrue($this->client->getResponse()->isNotFound());
        $this->assertEquals($error, "User doesn't exist.");

    }
}