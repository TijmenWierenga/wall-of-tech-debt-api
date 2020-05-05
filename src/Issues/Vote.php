<?php
declare(strict_types=1);

namespace App\Issues;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="issue_votes",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="issue_user", columns={"user_id", "issue_id"})
 *     }
 * )
 */
final class Vote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $userId;
    /**
     * @ORM\ManyToOne(targetEntity="App\Issues\Issue", inversedBy="votes")
     */
    private Issue $issue;
    /**
     * @ORM\Column(type="integer")
     */
    private int $amount = 0;

    public function __construct(Issue $issue, UuidInterface $userId)
    {
        $this->userId = $userId;
        $this->issue = $issue;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function increment(int $amount): void
    {
        $this->amount += $amount;
    }

    public function decrement(int $amount): void
    {
        if ($this->amount <= 0) {
            return;
        }

        $this->amount -= $amount;
    }
}
