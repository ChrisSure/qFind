<?php

namespace App\Tests\Functional\Controller\Auth;

use App\Entity\User\User;
use App\Entity\User\UserToken;
use App\Service\Helper\SerializeService;
use App\Tests\Functional\Base;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthControllerTest extends Base
{
    private $token;

    protected function setUp(): void
    {
       parent::setUp();
       $this->token = $this->faker->sentence;
    }

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

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_BAD_REQUEST);
        $this->assertEquals($response->error, 'User who has this email already exists.');
    }

    /**
     * @test
     */
    public function confirmUser(): void
    {
        $user = $this->createUser();
        $queryData = ['user_id' => $user->getId(), 'token' => $this->token];
        $this->client->request('GET', '/auth/confirm-register', $queryData);

        $response = json_decode($this->client->getResponse()->getContent());
        $this->revertChanges($user->getEmail());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_string($response->token));
    }

    private function createUser(): User
    {
        $userTokenObject = new UserToken($this->token, 4102437600);

        $serializeService = new SerializeService();

        $user = new User();
        $user->setEmail($this->faker->email);
        $user->setStatus(User::$STATUS_NEW);
        $user->setRoles(User::$ROLE_USER);
        $user->setPasswordHash($this->faker->password);
        $user->onPrePersist()->onPreUpdate();
        $user->setToken($serializeService->serialize($userTokenObject));

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }

    private function revertChanges($email): void
    {
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $email]);
        $this->manager->remove($user);
        $this->manager->flush();
    }
}