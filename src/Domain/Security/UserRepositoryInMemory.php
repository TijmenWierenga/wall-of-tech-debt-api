<?php

declare(strict_types=1);

namespace App\Domain\Security;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Repositories\InMemoryRepository;

final class UserRepositoryInMemory implements UserRepository
{
    /**
     * @psalm-var InMemoryRepository<User>
     */
    private InMemoryRepository $storage;

    public function __construct(User ...$users)
    {
        $this->storage = new InMemoryRepository($users);
    }

    public function find(UuidInterface $id): User
    {
        $user = $this->storage->find(fn (User $user): bool => $user->getId()->toString() === $id->toString());

        if (! $user) {
            throw UserNotFoundException::withId($id);
        }

        return $user;
    }

    public function findByUsername(string $username): User
    {
        $user = $this->storage->find(fn (User $user): bool => $user->getUsername() === $username);

        if (! $user) {
            throw UserNotFoundException::withUsername($username);
        }

        return $user;
    }

    public function save(User $user): void
    {
        $this->storage->remove(fn (User $item): bool => $user->getId()->toString() === $item->getId()->toString());

        $this->storage->add($user);
    }

    public function all(): Collection
    {
        return new ArrayCollection($this->storage->all());
    }
}
