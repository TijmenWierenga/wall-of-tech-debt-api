<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class IssueService
{

    /** @var IssueRepository */
    private IssueRepository $issueRepository;

    public function __construct(IssueRepository $issueRepository)
    {
        $this->issueRepository = $issueRepository;
    }

    public function create(CreateIssueCommand $command, UuidInterface $authorId): Issue
    {
        $issue = new Issue(
            Uuid::uuid4(),
            $command->title,
            $authorId,
            new DateTimeImmutable()
        );

        return $this->issueRepository->save($issue);
    }

    public function getAll(): Collection
    {
        return $this->issueRepository->all();
    }
}
