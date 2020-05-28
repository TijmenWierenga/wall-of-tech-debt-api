<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\Issue;
use App\Domain\Issues\Vote;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class IssueTest extends TestCase
{
    public function testItReturnsAllVotesForAUser(): void
    {
        $userId = Uuid::uuid4();
        $issue = new Issue(Uuid::uuid4(), 'Missing docs', Uuid::uuid4(), new DateTimeImmutable());
        $vote = new Vote($issue, $userId);
        $issue->addVote($vote);

        $result = $issue->getVoteForUser($userId);

        static::assertEquals($result, $vote);
    }

    public function testItReturnsNoVotesWhenUserDidNotVoteOnTheIssue(): void
    {
        $userId = Uuid::uuid4();
        $issue = new Issue(Uuid::uuid4(), 'Missing docs', Uuid::uuid4(), new DateTimeImmutable());

        $result = $issue->getVoteForUser($userId);

        static::assertNull($result);
    }

    public function testItCanAddAVote(): void
    {
        $userId = Uuid::uuid4();
        $issue = new Issue(Uuid::uuid4(), 'Missing docs', Uuid::uuid4(), new DateTimeImmutable());

        $vote = new Vote($issue, $userId);

        static::assertEmpty($issue->getVotes());

        $issue->addVote($vote);

        static::assertContains($vote, $issue->getVotes());
    }
}
