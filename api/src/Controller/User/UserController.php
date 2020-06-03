<?php

namespace App\Controller\User;

use App\Entity\User\User;
use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/users")
 * @IsGranted("ROLE_ADMIN")
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

        try {
            $users = $this->userService->all($email, $status, $role, $page);
            $totalUsers = $this->userService->totalUsers($email, $status, $role);
            return new JsonResponse(
                [
                    'users' => $users,
                    'statusList' => User::statusList(),
                    'rolesList' => User::rolesList(),
                    'totalUsers' => $totalUsers
                ],  JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}