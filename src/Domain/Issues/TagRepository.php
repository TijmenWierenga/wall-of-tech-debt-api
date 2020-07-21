<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

interface TagRepository
{
    /**
     * @throws TagAlreadyExistsException
     */
    public function save(Tag $tag): void;

    /**
     * @throws TagNotFoundException
     */
    public function find(UuidInterface $tagId): Tag;

    /**
     * @return Collection<int, Tag>
     */
    public function all(): Collection;
}
