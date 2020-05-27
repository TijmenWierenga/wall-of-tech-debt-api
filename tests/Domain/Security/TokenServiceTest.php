<?php

declare(strict_types=1);

namespace App\Tests\Domain\Security;

use App\Domain\Security\TokenService;
use App\Domain\Security\User;
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

        $tokenService = new TokenService('f560257550dba19199e3d648756ecb09');
        $token = $tokenService->createToken($user);
        $parsed = $tokenService->parseToken($token);

        static::assertEquals($user->getId()->toString(), $parsed->getAudience());
    }
}
