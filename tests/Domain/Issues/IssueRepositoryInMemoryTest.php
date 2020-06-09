<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\IssueRepository;
use App\Domain\Issues\IssueRepositoryInMemory;

final class IssueRepositoryInMemoryTest extends IssueRepositoryTest
{
    public function getRepository(): IssueRepository
    {
        return new IssueRepositoryInMemory();
    }
}
