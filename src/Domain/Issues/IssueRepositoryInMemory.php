<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Repositories\InMemoryRepository;

final class IssueRepositoryInMemory implements IssueRepository
{
    /**
     * @psalm-var InMemoryRepository<Issue>
     */
    private InMemoryRepository $storage;

    public function __construct(InMemoryRepository $storage = null)
    {
        $this->storage = $storage ?? new InMemoryRepository();
    }

    public function save(Issue $issue): Issue
    {
        $this->storage->add($issue);

        return $issue;
    }

    /**
     * @return Collection<int, Issue>
     */
    public function all(): Collection
    {
        return new ArrayCollection($this->storage->all());
    }

    public function find(UuidInterface $issueId): Issue
    {
        $issue = $this->storage->find(fn (Issue $issue): bool => $issue->getId() === $issueId);

        if (! $issue instanceof Issue) {
            throw IssueNotFoundException::withId($issueId);
        }

        return $issue;
    }
}
