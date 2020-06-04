<?php

namespace App\Tests\Functional\Controller\User;

use App\Entity\User\User;
use App\Tests\Functional\Base;

class AllUserControllerTest extends Base
{
    /**
     * @test
     */
    public function all(): void
    {
        $this->signIn(User::$ROLE_ADMIN);

        $this->client->request('GET', '/users');

        $this->client->followRedirect();
        $response = json_decode($this->client->getResponse()->getContent());
        $users = json_decode($response->users)[0];
        $statusList = $response->statusList;
        $rolesList = $response->rolesList;

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_string($users->email));
        $this->assertEquals($users->email, "user@gmail.com");
        $this->assertTrue(isset($statusList));
        $this->assertEquals($statusList->new, "new");
        $this->assertTrue(isset($rolesList));
        $this->assertEquals($rolesList->ROLE_USER, "ROLE_USER");
    }
}