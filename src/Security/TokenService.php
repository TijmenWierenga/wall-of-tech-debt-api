<?php
declare(strict_types=1);

namespace App\Security;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use ParagonIE\Paseto\Builder;
use ParagonIE\Paseto\JsonToken;
use ParagonIE\Paseto\Keys\SymmetricKey;
use ParagonIE\Paseto\Parser;

final class TokenService
{
    private SymmetricKey $key;

    public function __construct(string $sharedKey)
    {
        $this->key = SymmetricKey::v2($sharedKey);
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
        $jsonToken = $parser->parse($token);
        $parser->validate($jsonToken, true);

        return $jsonToken;
    }
}