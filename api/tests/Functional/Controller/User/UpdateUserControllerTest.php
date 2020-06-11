<?php

namespace App\Tests\Functional\Controller\User;

use App\Entity\User\User;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateUserControllerTest extends Base
{
    /**
     * @test
     */
    public function updateErrorValidation(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['email' => ''];
        $this->client->request('PUT', '/users/2', [], [], [], json_encode($data));
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_BAD_REQUEST);
        $this->assertTrue(is_string($response->error));
    }

    /**
     * @test
     */
    public function updateNotFound(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['email' => 'super_admin@gmail.com', 'password' => '123', 'role' => User::$ROLE_ADMIN, 'status' => User::$STATUS_ACTIVE];
        $this->client->request('PUT', '/users/235', $data);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_NOT_FOUND);
        $this->assertEquals($response->error, 'User doesn\'t exist.');
    }

    /**
     * @test
     */
    public function createAlreadyIssetEmail(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['email' => 'super_admin@gmail.com', 'password' => '123', 'role' => User::$ROLE_ADMIN, 'status' => User::$STATUS_ACTIVE];
        $this->client->request('PUT', '/users/2', $data);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_BAD_REQUEST);
        $this->assertEquals($response->error, 'User who has this email already exists.');
    }

    /**
     * @test
     */
    public function updateSuccessfull(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $data = ['email' => 'admin_test@gmail.com', 'password' => '123', 'role' => User::$ROLE_ADMIN, 'status' => User::$STATUS_ACTIVE];
        $this->client->request('PUT', '/users/2', $data);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_OK);
        $this->assertEquals($response->message, 'Updated successfull');

        $this->revertChanges();
    }

    private function revertChanges()
    {
        $doctrine = self::$container->get('doctrine');
        $manager = $doctrine->getManager();
        $user = $doctrine->getRepository(User::class)->findOneBy(['email' => 'admin_test@gmail.com']);
        $user->setEmail('admin@gmail.com');
        $manager->persist($user);
        $manager->flush();
    }
}