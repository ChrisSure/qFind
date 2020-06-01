<?php

namespace App\Service\User;

use App\Repository\User\UserRepository;
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
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param SerializeService $serializeService
     */
    public function __construct(UserRepository $userRepository, SerializeService $serializeService)
    {
        $this->userRepository = $userRepository;
        $this->serializeService = $serializeService;
    }

    /**
     * Get all users
     *
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
}