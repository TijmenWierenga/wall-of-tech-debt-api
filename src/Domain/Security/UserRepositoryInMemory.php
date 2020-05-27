<?php

declare(strict_types=1);

namespace App\Security;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

final class UserRepositoryInMemory implements UserRepository
{
    /**
     * @var Collection<array-key, User>
     */
    public Collection $users;

    public function __construct(User ...$users)
    {
        $this->users = new ArrayCollection($users);
    }

    public function find(UuidInterface $id): User
    {
        $user = $this->users->filter(fn (User $user): bool => $user->getId()->toString() === $id->toString())->first();

        if (! $user) {
            throw UserNotFoundException::withId($id);
        }

        return $user;
    }

    public function findByUsername(string $username): User
    {
        $user = $this->users->filter(fn (User $user): bool => $user->getUsername() === $username)->first();

        if (! $user) {
            throw UserNotFoundException::withUsername($username);
        }

        return $user;
    }

    public function save(User $user): void
    {
        $newCollection = $this->users
            ->filter(fn (User $item): bool => $user->getId()->toString() !== $item->getId()->toString());

        $newCollection->add($user);

        $this->users = $newCollection;
    }
}
