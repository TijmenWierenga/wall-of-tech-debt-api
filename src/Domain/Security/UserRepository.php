<?php

declare(strict_types=1);

namespace App\Domain\Security;

use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

interface UserRepository
{
    /**
     * @throws UserNotFoundException
     */
    public function find(UuidInterface $id): User;

    /**
     * @throws UserNotFoundException
     */
    public function findByUsername(string $username): User;

    public function save(User $user): void;

    /**
     * @return Collection<int, User>
     */
    public function all(): Collection;
}
