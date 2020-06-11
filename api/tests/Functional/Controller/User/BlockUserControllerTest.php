<?php

namespace App\Tests\Functional\Controller\User;

use App\Entity\User\User;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\JsonResponse;

class BlockUserControllerTest extends Base
{
    /**
     * @test
     */
    public function blockNotFound(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $this->client->request('GET', '/users/239/block');
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_NOT_FOUND);
        $this->assertEquals($response->error, 'User doesn\'t exist.');
    }

    /**
     * @test
     */
    public function blockSuccess(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $this->client->request('GET', '/users/2/block');
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_OK);
        $this->assertEquals($response->message, 'You successfull block user');

        $this->revertChanges();
    }

    private function revertChanges()
    {
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'admin@gmail.com']);
        $user->setStatus(User::$STATUS_NEW);
        $this->manager->persist($user);
        $this->manager->flush();
    }
}