<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\TagRepository;
use App\Domain\Issues\TagRepositoryInMemory;

final class TagRepositoryInMemoryTest extends TagRepositoryTest
{
    protected function getRepository(): TagRepository
    {
        return new TagRepositoryInMemory();
    }
}
