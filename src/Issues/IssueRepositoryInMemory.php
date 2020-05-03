<?php
declare(strict_types=1);

namespace App\Issues;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class IssueRepositoryInMemory implements IssueRepository
{
    public array $issueCollection = [];

    public function save(Issue $issue): Issue
    {
        $this->issueCollection[] = $issue;

        return $issue;
    }

    public function all(): Collection
    {
        return new ArrayCollection($this->issueCollection);
    }
}
