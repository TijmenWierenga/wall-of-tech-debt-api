<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security;

use App\Domain\Security\User;
use App\Domain\Security\UserRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->getRepository();
    }

    abstract public function getRepository(): UserRepository;

    public function testItSavesAndFindsAUser(): void
    {
        $user = $this->makeUser();

        $this->repository->save($user);

        $result = $this->repository->find($user->getId());

        static::assertEquals($result, $user);
    }

    public function testItFindsAUserByUsername(): void
    {
        $user = $this->makeUser();

        $this->repository->save($user);

        $result = $this->repository->findByUsername('tijmen');

        static::assertEquals($result, $user);
    }

    public function testItReturnsAllUsers(): void
    {
        $first = $this->makeUser();
        $second = User::new(Uuid::uuid4(), 'paul', 'password');

        $this->repository->save($first);
        $this->repository->save($second);

        $all = $this->repository->all();

        static::assertCount(2, $all);
        static::assertContains($first, $all);
        static::assertContains($second, $all);
    }

    private function makeUser(): User
    {
        return User::new(Uuid::uuid4(), 'tijmen', 'password');
    }
}
