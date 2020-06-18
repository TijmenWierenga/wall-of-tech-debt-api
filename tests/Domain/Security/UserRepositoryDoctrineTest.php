<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security;

use App\Domain\Security\UserRepository;
use App\Domain\Security\UserRepositoryDoctrine;
use App\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;

final class UserRepositoryDoctrineTest extends UserRepositoryTest
{
    use DatabasePrimer;

    public function getRepository(): UserRepository
    {
        static::bootKernel();

        $this->prepareDatabaseSchema(static::$container->get(EntityManagerInterface::class));

        return static::$container->get(UserRepositoryDoctrine::class);
    }
}
