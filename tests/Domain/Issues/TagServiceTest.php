<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\TagRepositoryInMemory;
use App\Domain\Issues\TagService;
use PHPUnit\Framework\TestCase;

final class TagServiceTest extends TestCase
{
    public function testItCreatesANewTag(): void
    {
        $repository = new TagRepositoryInMemory();
        $service = new TagService($repository);

        $tag = $service->create('my-tag');

        static::assertEquals('my-tag', $tag->name);
        static::assertEquals($repository->find($tag->getId()), $tag);
    }
}
