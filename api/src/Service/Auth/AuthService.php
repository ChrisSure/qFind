<?php

namespace App\Service\Auth;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
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
     * AuthService constructor.
     *
     * @param UserRepository $userRepository
     * @param PasswordHashService $passwordHashService
     * @param JWTService $jwtService
     */
    public function __construct(
        UserRepository $userRepository,
        PasswordHashService $passwordHashService,
        JWTService $jwtService
    )
    {
        $this->passwordHashService = $passwordHashService;
        $this->userRepository = $userRepository;
        $this->jwtService = $jwtService;
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