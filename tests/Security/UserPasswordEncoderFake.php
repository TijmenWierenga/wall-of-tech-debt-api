<?php

namespace App\Tests\Security;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserPasswordEncoderFake implements UserPasswordEncoderInterface
{
    public function encodePassword(UserInterface $user, string $plainPassword): string
    {
        return 'hashed-password';
    }

    public function isPasswordValid(UserInterface $user, string $raw): bool
    {
        return $user->getPassword() === $raw;
    }

    public function needsRehash(UserInterface $user): bool
    {
        return $user->getPassword() === 'rehash';
    }
}
