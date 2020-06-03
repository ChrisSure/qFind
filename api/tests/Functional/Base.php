<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Base extends WebTestCase
{
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    protected function signInAdmin(): string
    {
        $postData = ['email' => 'admin@gmail.com', 'password' => '123', 'type' => 'admin'];

        $this->client->request(
            'POST',
            '/auth/signin',
            $postData
        );
        $response = json_decode($this->client->getResponse()->getContent());
        return $response->token;
    }
}