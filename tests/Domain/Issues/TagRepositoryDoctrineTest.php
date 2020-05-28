<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\TagRepository;
use App\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;

final class TagRepositoryDoctrineTest extends TagRepositoryTest
{
    use DatabasePrimer;

    protected function getRepository(): TagRepository
    {
        static::bootKernel();

        $this->prepareDatabaseSchema(static::$container->get(EntityManagerInterface::class));

        return self::$container->get(TagRepository::class);
    }
}
