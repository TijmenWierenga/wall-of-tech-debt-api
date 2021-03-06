<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use Ramsey\Uuid\UuidInterface;

final class VoteService
{
    private IssueRepository $issueRepository;

    public function __construct(IssueRepository $issueRepository)
    {
        $this->issueRepository = $issueRepository;
    }

    public function addVote(UuidInterface $issueId, UuidInterface $userId, int $amount): void
    {
        $issue = $this->issueRepository->find($issueId);

        $vote = $issue->getVoteForUser($userId);

        if (! $vote) {
            $vote = new Vote($issue, $userId);
            $vote->increment($amount);
            $issue->addVote($vote);

            $this->issueRepository->save($issue);

            return;
        }

        $vote->increment($amount);

        $this->issueRepository->save($issue);
    }

    public function removeVote(UuidInterface $issueId, UuidInterface $userId, int $amount): void
    {
        $issue = $this->issueRepository->find($issueId);

        $vote = $issue->getVoteForUser($userId);

        if (! $vote) {
            return;
        }

        $vote->decrement($amount);

        $this->issueRepository->save($issue);
    }
}
