<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

final class TagService
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function create(string $name): Tag
    {
        $tag = new Tag(Uuid::uuid4(), $name);

        $this->tagRepository->save($tag);

        return $tag;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getAll(): Collection
    {
        return $this->tagRepository->all();
    }
}
