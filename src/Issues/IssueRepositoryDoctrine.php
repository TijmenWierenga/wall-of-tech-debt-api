<?php
declare(strict_types=1);

namespace App\Issues;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

final class IssueRepositoryDoctrine implements IssueRepository
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Issue $issue): Issue
    {
        $this->entityManager->persist($issue);
        $this->entityManager->flush();

        return $issue;
    }

    /**
     * @return Collection<Issue>
     */
    public function all(): Collection
    {
        $repository = $this->entityManager->getRepository(Issue::class);

        return new ArrayCollection($repository->findAll());
    }
}
