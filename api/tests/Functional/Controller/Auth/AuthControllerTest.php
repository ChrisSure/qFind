<?php

namespace App\Tests\Functional\Controller\Auth;

use App\Tests\Functional\Base;

class AuthControllerTest extends Base
{
    /**
     * @test
     */
    public function signInUser(): void
    {
        $postData = ['email' => 'user@gmail.com', 'password' => '123', 'type' => 'site'];

        $this->client->request(
            'POST',
            '/auth/signin',
            $postData
        );

        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_string($response->token));
    }

    /**
     * @test
     */
    public function signInAdmin(): void
    {
        $postData = ['email' => 'admin@gmail.com', 'password' => '123', 'type' => 'admin'];

        $this->client->request(
            'POST',
            '/auth/signin',
            $postData
        );

        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_string($response->token));
    }

    /**
     * @test
     */
    /*public function signUpAdmin(): void
    {
        $postData = ['email' => 'admin@gmail.com', 'password' => '123', 'type' => 'admin'];

        $this->client->request(
            'POST',
            '/auth/signin',
            $postData
        );

        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_string($response->token));
    }*/
}