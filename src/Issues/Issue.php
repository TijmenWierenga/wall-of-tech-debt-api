<?php
declare(strict_types=1);

namespace App\Issues;

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
     * @ORM\OneToMany(targetEntity="App\Issues\Vote", mappedBy="issue", fetch="EAGER")
     */
    private Collection $votes;

    public function __construct(UuidInterface $id, string $title, UuidInterface $authorId, DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->title = $title;
        $this->authorId = $authorId;
        $this->createdAt = $createdAt;
        $this->votes = new ArrayCollection();
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

    public function getVotes(): Collection
    {
        return $this->votes;
    }
}
