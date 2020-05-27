<?php

declare(strict_types=1);

namespace App\Domain\Security;

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

    public function findByUsername(string $username): User
    {
        $repository = $this->entityManager->getRepository(User::class);

        $user = $repository->findOneBy([
            'username' => $username
        ]);

        if (! $user) {
            throw UserNotFoundException::withUsername($username);
        }

        return $user;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
