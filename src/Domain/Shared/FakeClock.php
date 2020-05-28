<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use DateTimeImmutable;

final class FakeClock implements Clock
{
    private DateTimeImmutable $time;

    public function __construct(DateTimeImmutable $time = null)
    {
        $this->time = $time ?? new DateTimeImmutable();
    }

    public function now(): DateTimeImmutable
    {
        return $this->time;
    }

    public function timeTravelTo(DateTimeImmutable $time): void
    {
        $this->time = $time;
    }
}
