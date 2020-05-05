<?php
declare(strict_types=1);

namespace App\Issues;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

final class IssueRepositoryInMemory implements IssueRepository
{
    public Collection $issueCollection;

    public function __construct()
    {
        $this->issueCollection = new ArrayCollection();
    }


    public function save(Issue $issue): Issue
    {
        $this->issueCollection[] = $issue;

        return $issue;
    }

    public function all(): Collection
    {
        return $this->issueCollection;
    }

    public function find(UuidInterface $issueId): Issue
    {
        $issue = $this->issueCollection->filter(fn (Issue $issue): bool => $issue->getId() === $issueId)->first();

        if (! $issue) {
            throw IssueNotFoundException::withId($issueId);
        }

        return $issue;
    }
}
