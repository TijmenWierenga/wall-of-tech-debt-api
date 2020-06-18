<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security;

use App\Domain\Security\UserRepository;
use App\Domain\Security\UserRepositoryInMemory;

final class UserRepositoryInMemoryTest extends UserRepositoryTest
{
    public function getRepository(): UserRepository
    {
        return new UserRepositoryInMemory();
    }
}
