<?php

declare(strict_types=1);

namespace App\Security;

use Ramsey\Uuid\UuidInterface;

interface UserRepository
{
    public function find(UuidInterface $id): User;
}
