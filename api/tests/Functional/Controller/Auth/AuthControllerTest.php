<?php

namespace App\Tests\Functional\Controller\Auth;

use App\Entity\User\User;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    public function signUpUser(): void
    {
        $postData = ['email' => $email = 'user2@gmail.com', 'password' => '123', 'type' => 'site'];

        $this->client->request(
            'POST',
            '/auth/signup',
            $postData
        );

        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($response->message, 'For confirm registration check your email');
        $this->revertChanges($email);
    }

    /**
     * @test
     */
    public function signUpIssetUser(): void
    {
        $postData = ['email' => 'user@gmail.com', 'password' => '123', 'type' => 'site'];

        $this->client->request(
            'POST',
            '/auth/signup',
            $postData
        );

        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_NOT_FOUND);
        $this->assertEquals($response->error, 'User who has this email already exists.');
    }

    private function revertChanges($email)
    {
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $email]);
        $this->manager->remove($user);
        $this->manager->flush();
    }
}