<?php
declare(strict_types=1);

namespace App\Security;

use App\Shared\NotFoundException;

final class UserNotFoundException extends NotFoundException
{
    protected static function getModelFCQN(): string
    {
        return User::class;
    }
}
