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
        return $this->serializeService->serialize($this->userRepository->findAll());
    }

    /**
     * Return total users
     *
     * @return int
     */
    public function totalUsers(): int
    {
        return 3;
        //return $this->userRepository->count();
    }
}