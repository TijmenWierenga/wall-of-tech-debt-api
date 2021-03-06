<?php

declare(strict_types=1);

namespace App\Domain\Security;

use App\Domain\Shared\NotFoundException;

final class UserNotFoundException extends NotFoundException
{
    protected static function getModelFCQN(): string
    {
        return User::class;
    }

    public static function withUsername(string $username): self
    {
        return new self(sprintf('User \'%s\' does not exist', $username));
    }
}
