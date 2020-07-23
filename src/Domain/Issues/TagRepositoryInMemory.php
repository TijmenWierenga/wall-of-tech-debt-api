<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

final class TagRepositoryInMemory implements TagRepository
{
    /** @var Collection<int,Tag> */
    public Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function save(Tag $tag): void
    {
        $result = $this->tags->filter(fn (Tag $item): bool => $tag->name === $item->name);

        if (! $result->isEmpty()) {
            throw TagAlreadyExistsException::withName($tag->name);
        }

        $this->tags->add($tag);
    }

    public function find(UuidInterface $tagId): Tag
    {
        $result = $this->tags
            ->filter(fn (Tag $item): bool => $tagId->toString() === $item->getId()->toString())
            ->first();

        if (! $result) {
            throw TagNotFoundException::withId($tagId);
        }

        return $result;
    }

    public function all(): Collection
    {
        return $this->tags;
    }
}
