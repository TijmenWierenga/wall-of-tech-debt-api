<?php

declare(strict_types=1);

namespace App\Domain\Security;

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
}
