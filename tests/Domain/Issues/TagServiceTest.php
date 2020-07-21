<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\Tag;
use App\Domain\Issues\TagRepositoryInMemory;
use App\Domain\Issues\TagService;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

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

    public function testItReturnsAllTags(): void
    {
        $tags = [
            new Tag(Uuid::uuid4(), 'first'),
            new Tag(Uuid::uuid4(), 'second'),
            new Tag(Uuid::uuid4(), 'third')
        ];

        $repository = new TagRepositoryInMemory();

        foreach ($tags as $tag) {
            $repository->save($tag);
        }

        $service = new TagService($repository);

        static::assertEquals($tags, $service->getAll()->toArray());
    }
}
