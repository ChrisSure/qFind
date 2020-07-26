<?php

namespace App\Service\Auth;

use App\Entity\User\SocialUser;
use App\Entity\User\User;
use App\Entity\User\UserToken;
use App\Repository\User\SocialUserRepository;
use App\Repository\User\UserRepository;
use App\Service\Email\AuthMailService;
use App\Service\Helper\SerializeService;
use App\Service\User\UserTokenService;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthService
{
    /**
     * @var PasswordHashService
     */
    private $passwordHashService;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var JWTService
     */
    private $jwtService;

    /**
     * @var UserTokenService
     */
    private $userTokenService;

    /**
     * @var SerializeService
     */
    private $serializeService;

    /**
     * @var AuthMailService
     */
    private $authMailService;


    /**
     * AuthService constructor.
     *
     * @param UserRepository $userRepository
     * @param PasswordHashService $passwordHashService
     * @param JWTService $jwtService
     * @param UserTokenService $userTokenService
     * @param SerializeService $serializeService
     * @param AuthMailService $authMailService
     * @param SocialUserRepository $socialUserRepository
     */
    public function __construct(
        UserRepository $userRepository,
        PasswordHashService $passwordHashService,
        JWTService $jwtService,
        UserTokenService $userTokenService,
        SerializeService $serializeService,
        AuthMailService $authMailService,
        SocialUserRepository $socialUserRepository
    )
    {
        $this->passwordHashService = $passwordHashService;
        $this->userRepository = $userRepository;
        $this->jwtService = $jwtService;
        $this->userTokenService = $userTokenService;
        $this->serializeService = $serializeService;
        $this->authMailService = $authMailService;
        $this->socialUserRepository = $socialUserRepository;
    }

    /**
     * Login user
     *
     * @param array $data
     * @return string
     */
    public function loginUser(array $data): string
    {
        $user = $this->checkCredentials($data);
        return $this->jwtService->create($user);
    }

    /**
     * Create user on site
     *
     * @param array $data
     * @return User $user
     */
    public function createUser(array $data): User
    {
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        if ($user !== null) {
            throw new \InvalidArgumentException("User who has this email already exists.");
        }

        $token = $this->userTokenService->generateToken();
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPasswordHash($this->passwordHashService->hashPassword($user, $data['password']));
        $user->setRoles(User::$ROLE_USER);
        $user->setToken($this->serializeService->serialize($token));
        $user->setStatus(User::$STATUS_NEW)->onPrePersist()->onPreUpdate();
        $this->userRepository->save($user);

        $this->authMailService->sendCheckRegistration($user, $token->getToken());

        return $user;
    }

    /**
     * Confirm register user
     *
     * @param array $data
     * @return string
     */
    public function confirmRegisterUser(array $data): string
    {
        $user = $this->userRepository->get($data['id']);
        $this->hasToken($user);
        $this->checkToken($user, $data['token']);

        $user->setStatus(User::$STATUS_ACTIVE);
        $user->setToken(null);
        $user->onPreUpdate();
        $this->userRepository->save($user);

        return $this->jwtService->create($user);
    }

    /**
     * Forget user password
     *
     * @param array $data
     * @return User $user
     */
    public function forgetPassword(array $data): User
    {
        $user = $this->userRepository->getByEmail($data['email']);

        if ($user->getToken() != null) {
            $tokenObject = $this->serializeService->deserialize($user->getToken(), UserToken::class, 'json');
            if (!$tokenObject->isExpiredToken()) {
                throw new \BadMethodCallException('You change your password too often.');
            }
        }

        $token = $this->userTokenService->generateToken();

        $user->setToken($this->serializeService->serialize($token))->onPreUpdate();
        $this->userRepository->save($user);

        $this->authMailService->sendForgetPassword($user, $token->getToken());

        return $user;
    }

    /**
     * Confirm new password
     *
     * @param array $data
     * @return User
     */
    public function confirmNewPassword(array $data): User
    {
        $user = $this->userRepository->get($data['id']);
        $this->hasToken($user);
        $this->checkToken($user, $data['token']);
        return $user;
    }

    /**
     * Set new user password
     *
     * @param array $data
     * @param int $id
     * @return string
     */
    public function newPassword(array $data, $id): string
    {
        $user = $this->userRepository->get($id);
        $this->hasToken($user);

        $user->setPasswordHash($this->passwordHashService->hashPassword($user, $data['password']));
        $user->setToken(null);
        $user->onPreUpdate();
        $this->userRepository->save($user);

        return $this->jwtService->create($user);
    }

    /**
     * Login user using social networks
     *
     * @param array $data
     * @return string
     */
    public function loginSocialUser(array $data): string
    {
        if (!in_array($data['provider'], SocialUser::$listProviders))
            throw new NotFoundHttpException('Provider' . $data['provider'] . ' doesn\'t exist.');

        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        if (!$user) {
            $user = new User();
            $user->setEmail($data['email'])->setRoles(User::$ROLE_USER)->setStatus(User::$STATUS_ACTIVE)
                ->onPrePersist()->onPreUpdate();
            $this->userRepository->save($user);
        }
        if (!in_array($data['provider'], $this->getArrayProviders($user))) {
            $socialUser = new SocialUser();
            $socialUser->setUser($user)->setAppId($data['app_id'])->setProvider($data['provider'])
                ->setName($data['name'])->setImage($data['image']);
            $this->socialUserRepository->save($socialUser);
        } else {
            $result = false;
            foreach($user->getSocial() as $value) {
                if ($value->getAppId() === $data['app_id'] && $value->getProvider() === $data['provider']) {
                    $result = true;
                }
            }
            if (!$result) {
                throw new \InvalidArgumentException('Mistake data.');
            }
        }

        return $this->jwtService->create($user);
    }

    /**
     * Check user token
     *
     * @param User $user
     * @param string $token
     *
     * @return void
     */
    private function checkToken(User $user, string $token): void
    {
        $tokenObject = $this->serializeService->deserialize($user->getToken(), UserToken::class, 'json');
        if ($tokenObject->getToken() != $token) {
            throw new \BadMethodCallException('You have missed data.');
        }
        if ($tokenObject->isExpiredToken()) {
            throw new \InvalidArgumentException('Token time has overed.');
        }
    }

    /**
     * Check if user has token
     *
     * @param User $user
     * @return bool
     */
    private function hasToken(User $user): bool
    {
        if ($user->getToken() === null) {
            throw new NotFoundHttpException('Your user doesn\'t have token.');
        }
        return true;
    }

    /**
     * Return array social providers
     * @param User $user
     * @return array
     */
    private function getArrayProviders(User $user): array
    {
        $array_providers = [];
        foreach ($user->getSocial() as $value) {
            $array_providers[] = $value->getProvider();
        }
        return $array_providers;
    }

    /**
     * Check user credentials
     *
     * @param array $data
     * @return User
     * @throws NotFoundHttpException
     * @throws AccessDeniedHttpException
     */
    private function checkCredentials(array $data): User
    {
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);

        if (!$user || !$this->passwordHashService->checkPassword($data['password'], $user))
            throw new NotFoundHttpException('You have entered mistake login or password.');

        if ($user->getStatus() != User::$STATUS_ACTIVE)
            throw new AccessDeniedHttpException('You didn\'t accept your email.');

        if ($data['type'] === 'admin' && ($user->getRoles()[0] !== $user::$ROLE_ADMIN && $user->getRoles()[0] !== $user::$ROLE_SUPER_ADMIN))
            throw new AccessDeniedHttpException('You don\'t have permission.');

        return $user;
    }

}