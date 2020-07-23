<?php

namespace App\Tests\Functional\Controller\User;

use App\Entity\User\User;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreateUserControllerTest extends Base
{
    /**
     * @test
     */
    public function createErrorValidation(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['email' => ''];
        $this->client->request('POST', '/users', $data);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_BAD_REQUEST);
        $this->assertTrue(is_string($response->error));
    }

    /**
     * @test
     */
    public function createAlreadyIssetEmail(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['email' => 'admin@gmail.com', 'password' => '123', 'role' => User::$ROLE_ADMIN, 'status' => User::$STATUS_ACTIVE];
        $this->client->request('POST', '/users', $data);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_BAD_REQUEST);
        $this->assertEquals($response->error, 'User who has this email already exists.');
    }

    /**
     * @test
     */
    public function createSuccessfull(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['email' => $email = 'admin_test@gmail.com', 'password' => '123', 'role' => User::$ROLE_ADMIN, 'status' => User::$STATUS_ACTIVE];
        $this->client->request('POST', '/users', $data);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_CREATED);
        $this->assertEquals($response->message, 'Created successfull');

        $this->revertChanges($email);
    }

    private function revertChanges($email)
    {
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $email]);
        $this->manager->remove($user);
        $this->manager->flush();
    }
}