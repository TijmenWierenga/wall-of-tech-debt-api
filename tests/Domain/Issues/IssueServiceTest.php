<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\IssueRepository;
use App\Domain\Issues\IssueService;
use App\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class IssueServiceTest extends KernelTestCase
{
    use DatabasePrimer;

    private IssueService $issueService;

    public function setUp(): void
    {
        static::bootKernel();

        $this->prepareDatabaseSchema(static::$container->get(EntityManagerInterface::class));

        $this->issueService = static::$container->get(IssueService::class);
    }

    public function testItCreatesANewTag(): void
    {
        $issue = $this->issueService->create('Missing docs', Uuid::uuid4());

        $issueRepository = static::$container->get(IssueRepository::class);

        $result = $issueRepository->find($issue->getId());

        static::assertEquals($result, $issue);
    }
}
