<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Repositories\InMemoryRepository;

final class TagRepositoryInMemory implements TagRepository
{
    /**
     * @psalm-var InMemoryRepository<Tag>
     */
    private InMemoryRepository $storage;

    public function __construct(InMemoryRepository $storage = null)
    {
        $this->storage = $storage ?? new InMemoryRepository();
    }

    public function save(Tag $tag): void
    {
        $result = $this->storage->find(fn (Tag $item): bool => $tag->name === $item->name);

        if ($result instanceof Tag) {
            throw TagAlreadyExistsException::withName($tag->name);
        }

        $this->storage->add($tag);
    }

    public function find(UuidInterface $tagId): Tag
    {
        $result = $this->storage->find(fn (Tag $item): bool => $tagId->toString() === $item->getId()->toString());

        if (! $result) {
            throw TagNotFoundException::withId($tagId);
        }

        return $result;
    }

    public function all(): Collection
    {
        return new ArrayCollection($this->storage->all());
    }
}
