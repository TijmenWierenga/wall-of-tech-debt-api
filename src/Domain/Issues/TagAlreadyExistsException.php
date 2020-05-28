<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use RuntimeException;
use Throwable;

final class TagAlreadyExistsException extends RuntimeException
{
    public static function withName(string $name, Throwable $previous = null): self
    {
        return new self(sprintf('A tag with name \'%s\' already exists', $name), 1, $previous);
    }
}
