<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\IssueRepository;
use App\Domain\Issues\IssueService;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class IssueServiceTest extends KernelTestCase
{
    private IssueService $issueService;

    public function setUp(): void
    {
        static::bootKernel();

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
