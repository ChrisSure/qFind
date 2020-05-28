<?php

namespace App\Tests\Functional\Controller\User;

use App\Tests\Functional\Base;

class AllUserControllerTest extends Base
{
    /**
     * @test
     */
    public function all(): void
    {
        $this->client->request(
            'GET',
            '/users'
        );

        $this->client->followRedirect();
        $response = json_decode($this->client->getResponse()->getContent());
        $users = $response->users;

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_string($users));
    }
}