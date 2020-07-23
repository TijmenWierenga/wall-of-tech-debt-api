<?php

declare(strict_types=1);

namespace App\Domain\Security;

use Doctrine\Common\Collections\Collection;

final class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAll(): Collection
    {
        return $this->userRepository->all();
    }
}
