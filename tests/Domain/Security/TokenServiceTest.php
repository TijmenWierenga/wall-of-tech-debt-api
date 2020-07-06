<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security;

use App\Domain\Security\TokenService;
use App\Domain\Security\User;
use App\Domain\Shared\FakeClock;
use App\Domain\Shared\SystemClock;
use DateInterval;
use ParagonIE\Paseto\Exception\RuleViolation;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class TokenServiceTest extends TestCase
{
    public function testItCreatesAToken(): void
    {
        $user = User::new(
            Uuid::fromString('5da9537b-bd7f-42c9-9072-8677f96c8808'),
            'tijmen',
            'fake-password'
        );

        $tokenService = new TokenService('f560257550dba19199e3d648756ecb09', new SystemClock());
        $token = $tokenService->createToken($user);
        $parsed = $tokenService->parseToken($token);

        static::assertEquals($user->getId()->toString(), $parsed->getAudience());
    }

    public function testItDoesNotAcceptAnExpiredToken(): void
    {
        $user = User::new(
            Uuid::fromString('5da9537b-bd7f-42c9-9072-8677f96c8808'),
            'tijmen',
            'fake-password'
        );

        $clock = new FakeClock();

        $tokenService = new TokenService('f560257550dba19199e3d648756ecb09', $clock);
        $token = $tokenService->createToken($user);

        // Token's are only valid for a day
        $clock->timeTravelTo($clock->now()->add(DateInterval::createFromDateString('1 day')));

        $this->expectException(RuleViolation::class);

        $tokenService->parseToken($token);
    }

    public function testItDoesNotAcceptATokenBeforeUsageIsAllowed(): void
    {
        $user = User::new(
            Uuid::fromString('5da9537b-bd7f-42c9-9072-8677f96c8808'),
            'tijmen',
            'fake-password'
        );

        $clock = new FakeClock();

        $tokenService = new TokenService('f560257550dba19199e3d648756ecb09', $clock);
        $token = $tokenService->createToken($user);

        // Cannot use token before it's handed out (how is that even possible :-p)
        $clock->timeTravelTo($clock->now()->sub(DateInterval::createFromDateString('1 second')));

        $this->expectException(RuleViolation::class);

        $tokenService->parseToken($token);
    }
}
