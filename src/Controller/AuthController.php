<?php
declare(strict_types=1);

namespace App\Controller;

use App\Security\LoginService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class AuthController
{
    private LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
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
}
