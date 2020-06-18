<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Domain\Security\User;
use App\Domain\Security\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/users", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->getAll();

        $data = $users->map(fn (User $user): array => [
            'id' => $user->getId()->toString(),
            'username' => $user->getUsername(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName()
        ])->toArray();

        return new JsonResponse($data);
    }
}
