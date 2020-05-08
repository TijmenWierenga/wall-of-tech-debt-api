<?php
declare(strict_types=1);

namespace App\Issues;

use Ramsey\Uuid\UuidInterface;
use RuntimeException;

abstract class NotFoundException extends RuntimeException
{
    abstract protected static function getModelFCQN(): string;

    public static function withId(UuidInterface $id): self
    {
        return new static(sprintf('(%s) %s was not found', static::getModelFCQN(), $id->toString()));
    }
}
