<?php

declare(strict_types=1);

namespace App\Domain\Security;

use ParagonIE\Paseto\Exception\PasetoException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

final class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private UserRepository $userRepository;
    private TokenService $tokenService;

    public function __construct(UserRepository $userRepository, TokenService $tokenService)
    {
        $this->userRepository = $userRepository;
        $this->tokenService = $tokenService;
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new Response(null, Response::HTTP_FORBIDDEN);
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization');
    }

    public function getCredentials(Request $request): string
    {
        return $request->headers->get('Authorization') ?? '';
    }

    public function getUser($credentials, UserProviderInterface $userProvider): User
    {
        if (! preg_match('/^Bearer\s(.+)$/', $credentials, $matches)) {
            throw new AuthenticationException('The Authorization header is not a valid Bearer token');
        }

        $rawToken = $matches[1];

        try {
            $jsonToken = $this->tokenService->parseToken($rawToken);
            $userId = Uuid::fromString($jsonToken->getAudience());

            return $this->userRepository->find($userId);
        } catch (UserNotFoundException $e) {
            throw new AuthenticationException('Could not find user based on token', 1, $e);
        } catch (PasetoException $e) {
            throw new AuthenticationException('Invalid token', 1, $e);
        }
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new Response(null, Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
