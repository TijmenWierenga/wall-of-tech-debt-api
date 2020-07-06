<?php

declare(strict_types=1);

namespace App\Domain\Security;

use App\Domain\Shared\Clock;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use ParagonIE\Paseto\Builder;
use ParagonIE\Paseto\JsonToken;
use ParagonIE\Paseto\Keys\SymmetricKey;
use ParagonIE\Paseto\Parser;
use ParagonIE\Paseto\Rules\NotExpired;
use ParagonIE\Paseto\Rules\ValidAt;

final class TokenService
{
    private SymmetricKey $key;
    private Clock $clock;

    public function __construct(string $sharedKey, Clock $clock)
    {
        $this->key = SymmetricKey::v2($sharedKey);
        $this->clock = $clock;
    }

    public function createToken(User $user): string
    {
        $builder = Builder::getLocal($this->key);
        $now = new DateTimeImmutable();

        return $builder
            ->setExpiration($now->add(DateInterval::createFromDateString('1 day')))
            ->setIssuedAt(DateTime::createFromImmutable($now))
            ->setNotBefore(DateTime::createFromImmutable($now))
            ->setAudience($user->getId()->toString())
            ->toString();
    }

    public function parseToken(string $token): JsonToken
    {
        $parser = Parser::getLocal($this->key);

        $now = DateTime::createFromImmutable($this->clock->now());
        
        $parser->addRule(new ValidAt($now));

        $jsonToken = $parser->parse($token);
        $parser->validate($jsonToken, true);

        return $jsonToken;
    }
}
