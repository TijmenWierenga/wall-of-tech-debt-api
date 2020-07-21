<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class TagRepositoryDoctrine implements TagRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Tag $tag): void
    {
        try {
            $this->entityManager->persist($tag);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw TagAlreadyExistsException::withName($tag->name);
        }
    }

    public function find(UuidInterface $tagId): Tag
    {
        $repository = $this->entityManager->getRepository(Tag::class);

        $tag = $repository->find($tagId);

        if (! $tag) {
            throw TagNotFoundException::withId($tagId);
        }

        return $tag;
    }

    public function all(): Collection
    {
        $repository = $this->entityManager->getRepository(Tag::class);

        return new ArrayCollection($repository->findAll());
    }
}
