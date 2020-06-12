<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Domain\Security\LoginService;
use App\Domain\Security\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

final class AuthController
{
    private LoginService $loginService;
    private Security $security;

    public function __construct(LoginService $loginService, Security $security)
    {
        $this->loginService = $loginService;
        $this->security = $security;
    }

    /**
     * @Route("/login", methods={"POST"})
     */
    public function login(Request $request): JsonResponse
    {
        $data = json_decode((string)$request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $token = $this->loginService->authenticate($data['username'], $data['password']);

        return new JsonResponse([
            'token' => $token
        ]);
    }

    /**
     * @Route("/me", methods={"GET"})
     */
    public function getAuthenticatedUser(): JsonResponse
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return new JsonResponse([
            'id' => $user->getId()->toString(),
            'username' => $user->getUsername(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName()
        ]);
    }
}
