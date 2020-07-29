<?php

namespace App\Tests\Functional\Controller\Auth;

use App\Entity\User\SocialUser;
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
        $queryData = ['id' => $user->getId(), 'token' => $this->token];
        $this->client->request('GET', '/auth/confirm-register', $queryData);

        $response = json_decode($this->client->getResponse()->getContent());
        $this->revertChanges($user->getEmail());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_string($response->token));
    }

    /**
     * @test
     */
    public function forgotPasswordNotFoundUser(): void
    {
        $postData = ['email' => 'user_incorect@gmail.com'];

        $this->client->request(
            'POST',
            '/auth/forget-password',
            $postData
        );

        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->client->getResponse()->getStatusCode(), JsonResponse::HTTP_NOT_FOUND);
        $this->assertEquals($response->error, 'User doesn\'t exist.');
    }

    /**
     * @test
     */
    public function forgotPassword(): void
    {
        $postData = ['email' => $email = 'user@gmail.com'];

        $this->client->request(
            'POST',
            '/auth/forget-password',
            $postData
        );

        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals($response->message, 'Check your email for the next step.');
        $this->revertForgotPasswordChanges($email);
    }

    /**
     * @test
     */
    public function confirmNewPassword(): void
    {
        $user = $this->createUser();
        $queryData = ['id' => $user->getId(), 'token' => $this->token];
        $this->client->request('GET', '/auth/confirm-new-password', $queryData);

        $response = json_decode($this->client->getResponse()->getContent());
        $this->revertChanges($user->getEmail());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($response->message, 'Confirmed');
    }

    /**
     * @test
     */
    public function newPassword(): void
    {
        $user = $this->createUser();
        $postData = ['password' => '123'];

        $this->client->request(
            'POST',
            '/auth/new-password/' . $user->getId(),
            $postData
        );

        $response = json_decode($this->client->getResponse()->getContent());
        $this->revertChanges($user->getEmail());

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_string($response->token));
    }

    /**
     * @test
     */
    public function loginSocial(): void
    {
        $user = $this->createUser();
        $postData = ['provider' => 'facebook', 'email' => $user->getEmail(), 'app_id' => $appId = 12345, 'name' => $this->faker->name, 'image' => $this->faker->sentence];

        $this->client->request(
            'POST',
            '/auth/signin-social',
            $postData
        );

        $response = json_decode($this->client->getResponse()->getContent());
        $this->revertChangesSocial($appId);

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

    private function revertChangesSocial($appId): void
    {
        $socialUser = $this->doctrine->getRepository(SocialUser::class)->findOneBy(['appId' => $appId]);
        $this->manager->remove($socialUser);
        $this->manager->flush();
    }

    private function revertChanges($email): void
    {
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $email]);
        $this->manager->remove($user);
        $this->manager->flush();
    }

    private function revertForgotPasswordChanges($email): void
    {
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $email]);
        $user->setToken(null);
        $this->manager->persist($user);
        $this->manager->flush();
    }
}