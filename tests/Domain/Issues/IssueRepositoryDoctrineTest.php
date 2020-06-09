<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\IssueRepository;
use App\Domain\Issues\IssueRepositoryDoctrine;
use App\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;

final class IssueRepositoryDoctrineTest extends IssueRepositoryTest
{
    use DatabasePrimer;

    public function getRepository(): IssueRepository
    {
        static::bootKernel();

        $container = static::$container;

        $this->prepareDatabaseSchema($container->get(EntityManagerInterface::class));

        return $container->get(IssueRepositoryDoctrine::class);
    }
}
