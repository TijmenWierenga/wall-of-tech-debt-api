<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\CreateIssueCommand;
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
        $command = CreateIssueCommand::fromArray([
            'title' => 'Missing docs',
            'tags' => []
        ]);

        $issue = $this->issueService->create($command, Uuid::uuid4());

        $issueRepository = static::$container->get(IssueRepository::class);

        $result = $issueRepository->find($issue->getId());

        static::assertEquals($result, $issue);
    }
}
