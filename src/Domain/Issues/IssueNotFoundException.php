<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use App\Domain\Shared\NotFoundException;

final class IssueNotFoundException extends NotFoundException
{
    protected static function getModelFCQN(): string
    {
        return Issue::class;
    }
}
