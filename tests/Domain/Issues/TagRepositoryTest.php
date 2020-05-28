<?php

declare(strict_types=1);

namespace App\Tests\Domain\Issues;

use App\Domain\Issues\Tag;
use App\Domain\Issues\TagAlreadyExistsException;
use App\Domain\Issues\TagNotFoundException;
use App\Domain\Issues\TagRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class TagRepositoryTest extends KernelTestCase
{
    private TagRepository $tagRepository;

    protected function setUp(): void
    {
        $this->tagRepository = $this->getRepository();
    }

    abstract protected function getRepository(): TagRepository;

    public function testItSavesAndFindsNewTag(): void
    {
        $tag = new Tag(Uuid::uuid4(), 'my-tag');

        $this->tagRepository->save($tag);

        $result = $this->tagRepository->find($tag->getId());

        static::assertEquals($tag, $result);
    }

    public function testItDoesNotAllowSavingATagWithADuplicateName(): void
    {
        $tag = new Tag(Uuid::uuid4(), 'my-tag');

        $this->tagRepository->save($tag);

        $tagWithSameName = new Tag(Uuid::uuid4(), 'my-tag');

        $this->expectException(TagAlreadyExistsException::class);

        $this->tagRepository->save($tagWithSameName);
    }

    public function testItThrowsAnErrorWhenATagCannotBeFound(): void
    {
        $this->expectException(TagNotFoundException::class);

        $this->tagRepository->find(Uuid::uuid4()); // Random Id
    }
}
