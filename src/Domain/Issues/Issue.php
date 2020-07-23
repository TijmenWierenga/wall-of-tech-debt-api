<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="issues")
 */
final class Issue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $id;
    /**
     * @ORM\Column(type="string")
     */
    private string $title;
    /**
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $authorId;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;
    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Issues\Vote",
     *     mappedBy="issue", fetch="EAGER",
     *     cascade={"persist", "remove"}
     * )
     * @var Collection<int, Vote>
     */
    private Collection $votes;
    /**
     * @var Collection<int, Tag>
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="issues", fetch="EAGER")
     * @ORM\JoinTable(name="issue_tags")
     */
    private Collection $tags;

    public function __construct(UuidInterface $id, string $title, UuidInterface $authorId, DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->title = $title;
        $this->authorId = $authorId;
        $this->createdAt = $createdAt;
        $this->votes = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthorId(): UuidInterface
    {
        return $this->authorId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addVote(Vote $vote): void
    {
        if (! $this->votes->contains($vote)) {
            $this->votes->add($vote);
        }
    }

    public function getVoteForUser(UuidInterface $userId): ?Vote
    {
        return $this->getVotes()
            ->filter(fn (Vote $vote): bool => $vote->getUserId()->toString() === $userId->toString())->first() ?: null;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
