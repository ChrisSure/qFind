<?php

namespace App\Controller\User;

use App\Service\User\UserService;
use App\Validation\Auth\CreateUserValidation;
use App\Validation\Auth\UpdateUserValidation;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
                    'totalUsers' => $totalUsers
                ],  JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/{id}",  methods={"GET"})
     * Get single users
     *
     * @param int $id
     * @return JsonResponse
     */
    public function single($id): JsonResponse
    {
        try {
            $user = $this->userService->single($id);
            return new JsonResponse(['user' => $user], JsonResponse::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("",  methods={"POST"})
     * Create users
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new CreateUserValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->userService->create($data);
            return new JsonResponse(['message' => "Created successfull"], JsonResponse::HTTP_CREATED);
        } catch(\InvalidArgumentException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/{id}",  methods={"PUT"})
     * Update users
     *
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new UpdateUserValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->userService->update($data, $id);
            return new JsonResponse(['message' => "Updated successfull"], JsonResponse::HTTP_OK);
        } catch(NotFoundHttpException $e) {
                return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch(\InvalidArgumentException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (DBALException $e) {
            return new JsonResponse(["error" => "User who has this email already exists."], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/{id}",  methods={"DELETE"})
     * Delete user
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        try {
            $user = $this->userService->delete($id);
            return new JsonResponse(['message' => "You successfull deleted user"], JsonResponse::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/{id}/activate",  methods={"GET"})
     * Activate user
     *
     * @param int $id
     * @return JsonResponse
     */
    public function activate($id): JsonResponse
    {
        try {
            $user = $this->userService->activate($id);
            return new JsonResponse(['message' => "You successfull activate user"], JsonResponse::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/{id}/block",  methods={"GET"})
     * Block user
     *
     * @param int $id
     * @return JsonResponse
     */
    public function block($id): JsonResponse
    {
        try {
            $user = $this->userService->block($id);
            return new JsonResponse(['message' => "You successfull block user"], JsonResponse::HTTP_OK);
        } catch(NotFoundHttpException $e) {
            return new JsonResponse(["error" => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}