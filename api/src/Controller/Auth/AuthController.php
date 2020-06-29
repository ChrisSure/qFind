<?php

namespace App\Controller\Auth;

use App\Service\Auth\AuthService;
use App\Service\Auth\JWTService;
use App\Service\User\UserService;
use App\Validation\Auth\UserAuthValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auth")
 */
class AuthController extends AbstractController
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var JWTService
     */
    private $jwtService;

    /**
     * AuthController constructor.
     *
     * @param AuthService $authService
     * @param JWTService $jwtService
     */
    public function __construct(AuthService $authService, JWTService $jwtService)
    {
        $this->authService = $authService;
        $this->jwtService = $jwtService;
    }

    /**
     * @Route("/signin",  methods={"POST"})
     * Sign in user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function signIn(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new UserAuthValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $token = $this->authService->loginUser($data);
            return new JsonResponse(['token' => $token], JsonResponse::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/signup",  methods={"POST"})
     * Sign up user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function signUp(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new UserAuthValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $this->authService->createUser($data);
            return new JsonResponse(['message' => "For confirm registration check your email"], JsonResponse::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}