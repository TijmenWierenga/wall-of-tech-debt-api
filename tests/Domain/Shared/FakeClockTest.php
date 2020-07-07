<?php

declare(strict_types=1);

namespace App\Tests\Domain\Shared;

use App\Domain\Shared\FakeClock;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class FakeClockTest extends TestCase
{
    public function testItDefaultsToTheCurrentTime(): void
    {
        $now = new DateTimeImmutable();
        $clock = new FakeClock();

        static::assertEqualsWithDelta($now->getTimestamp(), $clock->now()->getTimestamp(), 1);
    }

    public function testItCanBeInstantiatedWithARandomTime(): void
    {
        $time = new DateTimeImmutable('yesterday');
        $clock = new FakeClock($time);

        static::assertEquals($time, $clock->now());
    }

    public function testItCanTimeTravelToADifferentTime(): void
    {
        $yesterday = new DateTimeImmutable('yesterday');
        $clock = new FakeClock($yesterday);

        static::assertEquals($yesterday, $clock->now());

        $now = new DateTimeImmutable();
        $clock->timeTravelTo($now);

        static::assertEquals($now, $clock->now());
    }
}
