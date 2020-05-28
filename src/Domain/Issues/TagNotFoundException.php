<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use App\Domain\Shared\NotFoundException;

final class TagNotFoundException extends NotFoundException
{
    protected static function getModelFCQN(): string
    {
        return Tag::class;
    }
}
