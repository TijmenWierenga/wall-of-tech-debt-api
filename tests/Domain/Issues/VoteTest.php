<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\Issue;
use App\Domain\Issues\Vote;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class VoteTest extends TestCase
{
    public function testIncrementDecrement(): void
    {
        $issue = new Issue(Uuid::uuid4(), 'demo-issue', Uuid::uuid4(), new DateTimeImmutable());
        $vote = new Vote($issue, Uuid::uuid4());

        static::assertEquals(0, $vote->getAmount());

        $vote->increment(1);

        static::assertEquals(1, $vote->getAmount());

        $vote->increment(2);

        static::assertEquals(3, $vote->getAmount());

        $vote->decrement(1);

        static::assertEquals(2, $vote->getAmount());

        $vote->decrement(2);

        static::assertEquals(0, $vote->getAmount());
    }

    public function testDecrementCannotGoBelowZero(): void
    {
        $issue = new Issue(Uuid::uuid4(), 'demo-issue', Uuid::uuid4(), new DateTimeImmutable());
        $vote = new Vote($issue, Uuid::uuid4());

        $vote->increment(4);

        static::assertEquals(4, $vote->getAmount());

        $vote->decrement(5);

        static::assertEquals(0, $vote->getAmount());
    }
}
