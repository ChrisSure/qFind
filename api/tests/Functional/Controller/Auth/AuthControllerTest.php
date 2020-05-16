<?php

namespace App\Tests\Functional\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @test
     */
    public function checkEntity(): void
    {
        $postData = ['email' => 'user@gmail.com', 'password' => '123'];

        $this->client->request(
            'POST',
            '/auth/signin',
            $postData
        );

        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_string($response->token));
    }
}