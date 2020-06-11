<?php

namespace App\Tests\Functional\Controller\User;

use App\Entity\User\User;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteUserControllerTest extends Base
{
    /**
     * @test
     */
    public function deleteNotFound(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $this->client->request('DELETE', '/users/239');
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_NOT_FOUND);
        $this->assertEquals($response->error, 'User doesn\'t exist.');
    }

    /**
     * @test
     */
    public function deleteSuccess(): void
    {
        $id = $this->createUser();

        $this->signIn(User::$ROLE_ADMIN);
        $this->client->request('DELETE', '/users/' . $id);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_OK);
        $this->assertEquals($response->message, 'You successfull deleted user');
    }

    private function createUser(): int
    {
        $user = new User();
        $user->setEmail('user4@gmail.com');
        $user->setRoles(User::$ROLE_USER);
        $user->setStatus(User::$STATUS_ACTIVE);
        $user->setPasswordHash(123);
        $user->onPrePersist()->onPreUpdate();
        $this->manager->persist($user);
        $this->manager->flush();
        return $user->getId();
    }
}