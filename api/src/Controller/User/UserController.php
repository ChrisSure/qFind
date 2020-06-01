<?php

namespace App\Controller\User;

use App\Entity\User\User;
use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/",  methods={"GET"})
     * Get all users
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $email = $request->query->get('email');
        $status = $request->query->get('status');
        $role = $request->query->get('role');
        $page = $request->query->get('page');

        $users = $this->userService->all($email, $status, $role, $page);
        $totalUsers = $this->userService->totalUsers($email, $status, $role);
        $statusList = User::statusList();
        $rolesList = User::rolesList();
        return new JsonResponse(['users' => $users, 'statusList' => $statusList, 'rolesList' => $rolesList, 'totalUsers' => $totalUsers], 200);
    }
}