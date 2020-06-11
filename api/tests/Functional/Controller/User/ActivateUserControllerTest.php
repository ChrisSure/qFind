<?php

namespace App\Tests\Functional\Controller\User;

use App\Entity\User\User;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\JsonResponse;

class ActivateUserControllerTest extends Base
{
    /**
     * @test
     */
    public function activateNotFound(): void
    {
        $this->signIn(User::$ROLE_ADMIN);
        $this->client->request('GET', '/users/239/activate');
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_NOT_FOUND);
        $this->assertEquals($response->error, 'User doesn\'t exist.');
    }

    /**
     * @test
     */
    public function activateSuccess(): void
    {
        $id = $this->changeDataUser();

        $this->signIn(User::$ROLE_ADMIN);
        $this->client->request('GET', '/users/'. $id .'/activate');
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_OK);
        $this->assertEquals($response->message, 'You successfull activate user');
    }

    private function changeDataUser(): int
    {
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => 'admin@gmail.com']);
        $user->setStatus(User::$STATUS_NEW);
        $this->manager->persist($user);
        $this->manager->flush();
        return $user->getId();
    }
}