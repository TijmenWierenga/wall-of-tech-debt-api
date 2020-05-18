<?php
declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class LoginService
{
    private UserRepository $userRepository;
    private UserPasswordEncoderInterface $passwordEncoder;
    private TokenService $tokenService;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        TokenService $tokenService
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenService = $tokenService;
    }

    public function authenticate(string $username, string $password): string
    {
        $user = $this->userRepository->findByUsername($username);

        if (! $this->passwordEncoder->isPasswordValid($user, $password)) {
            throw new AuthenticationException('Invalid username/password combination');
        }

        if ($this->passwordEncoder->needsRehash($user)) {
             $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
             $this->userRepository->save($user);
        }

        return $this->tokenService->createToken($user);
    }
}
