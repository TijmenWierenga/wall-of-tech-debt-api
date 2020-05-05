<?php

namespace App\Issues;

use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

interface IssueRepository
{
    public function save(Issue $issue): Issue;

    /**
     * @return Collection<Issue>
     */
    public function all(): Collection;

    /**
     * @throws IssueNotFoundException
     */
    public function find(UuidInterface $issueId): Issue;
}
