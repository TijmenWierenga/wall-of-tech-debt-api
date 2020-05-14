<?php
declare(strict_types=1);

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class UserRepositoryDoctrine implements UserRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(UuidInterface $id): User
    {
        $repository = $this->entityManager->getRepository(User::class);

        $user = $repository->find($id);

        if (! $user) {
            throw UserNotFoundException::withId($id);
        }

        return $user;
    }
}
