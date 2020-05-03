<?php

namespace App\Issues;

use Doctrine\Common\Collections\Collection;

interface IssueRepository
{
    public function save(Issue $issue): Issue;

    /**
     * @return Collection<Issue>
     */
    public function all(): Collection;
}
