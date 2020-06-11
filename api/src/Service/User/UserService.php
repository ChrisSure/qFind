<?php

namespace App\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\Auth\PasswordHashService;
use App\Service\Helper\SerializeService;

/**
 * Class UserService
 * @package App\Service\User
 */
class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var SerializeService
     */
    private $serializeService;

    /**
     * @var PasswordHashService
     */
    private $passwordHashService;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param SerializeService $serializeService
     */
    public function __construct(UserRepository $userRepository, SerializeService $serializeService, PasswordHashService $passwordHashService)
    {
        $this->userRepository = $userRepository;
        $this->serializeService = $serializeService;
        $this->passwordHashService = $passwordHashService;
    }

    /**
     * Get all users
     *
     * @param $email
     * @param $status
     * @param $role
     * @param $page
     * @return string
     */
    public function all($email, $status, $role, $page): string
    {
        return $this->serializeService->serialize($this->userRepository->getAll($email, $status, $role, $page));
    }

    /**
     * Return count users
     *
     * @param $email
     * @param $status
     * @param $role
     * @return int
     */
    public function totalUsers($email, $status, $role): int
    {
        return $this->userRepository->getCountUsers($email, $status, $role);
    }

    /**
     * Get single user
     *
     * @param $id
     * @return string
     */
    public function single($id): string
    {
        return $this->serializeService->serialize($this->userRepository->get($id));
    }

    /**
     * Create user
     *
     * @param array $data
     */
    public function create(array $data): void
    {
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        if ($user !== null) {
            throw new \InvalidArgumentException("User who has this email already exists.");
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPasswordHash($this->passwordHashService->hashPassword($user, $data['password']));
        $user->setRoles($data['role']);
        $user->setStatus($data['status'])->onPrePersist()->onPreUpdate();
        $this->userRepository->save($user);
    }

    /**
     * Update user
     *
     * @param array $data
     * @param int $id
     */
    public function update(array $data, $id): void
    {
        $user = $this->userRepository->get($id);
        $user->setEmail($data['email']);
        if ($data['password'] !== '') {
            $user->setPasswordHash($this->passwordHashService->hashPassword($user, $data['password']));
        }
        $user->setRoles($data['role']);
        $user->setStatus($data['status'])->onPreUpdate();
        $this->userRepository->save($user);
    }

    /**
     * Delete user
     *
     * @param $id
     * @return void
     */
    public function delete($id): void
    {
        $user = $this->userRepository->get($id);
        $this->userRepository->delete($user);
    }

    /**
     * Activate user
     *
     * @param $id
     * @return void
     */
    public function activate($id): void
    {
        $user = $this->userRepository->get($id);
        $user->setStatus(User::$STATUS_ACTIVE);
        $this->userRepository->save($user);
    }

    /**
     * Block user
     *
     * @param $id
     * @return void
     */
    public function block($id): void
    {
        $user = $this->userRepository->get($id);
        $user->setStatus(User::$STATUS_BLOCKED);
        $this->userRepository->save($user);
    }
}