<?php

namespace App\Controller\Auth;

use App\Repository\User\UserRepository;
use App\Service\Auth\JWTService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auth")
 */
class AuthController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var JWTService
     */
    private $jwtService;

    /**
     * AuthController constructor.
     * @param UserRepository $userRepository
     * @param JWTService $jwtService
     */
    public function __construct(UserRepository $userRepository, JWTService $jwtService)
    {
        $this->userRepository = $userRepository;
        $this->jwtService = $jwtService;
    }

    /**
     * @Route("/signin")
     * Sign in user
     * @param Request $request
     * @return JsonResponse
     */
    public function signIn(Request $request): JsonResponse
    {
        $jwt = $this->jwtService->create();
        return new JsonResponse(
            [
                'jwt' => $jwt,
            ],
            JsonResponse::HTTP_OK
        );
    }
}