<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\Issue;
use App\Domain\Issues\IssueNotFoundException;
use App\Domain\Issues\IssueRepository;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class IssueRepositoryTest extends KernelTestCase
{
    abstract public function getRepository(): IssueRepository;

    public function testItThrowsWhenIssueDoesNotExist(): void
    {
        $repository = $this->getRepository();

        $this->expectException(IssueNotFoundException::class);

        $repository->find(Uuid::uuid4());
    }

    public function testItSavesAndFindsAnIssue(): void
    {
        $repository = $this->getRepository();

        $issue = new Issue(Uuid::uuid4(), 'demo', Uuid::uuid4(), new DateTimeImmutable());

        $repository->save($issue);

        $result = $repository->find($issue->getId());

        static::assertEquals($issue, $result);
    }

    public function testItReturnsAllIssues(): void
    {
        $repository = $this->getRepository();

        $first = new Issue(Uuid::uuid4(), 'first', Uuid::uuid4(), new DateTimeImmutable());
        $second = new Issue(Uuid::uuid4(), 'second', Uuid::uuid4(), new DateTimeImmutable());

        $repository->save($first);
        $repository->save($second);

        $all = $repository->all();

        static::assertContains($first, $all);
        static::assertContains($second, $all);
    }
}
