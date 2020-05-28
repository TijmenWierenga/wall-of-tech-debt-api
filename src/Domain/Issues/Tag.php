<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="tags")
 */
final class Tag
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $id;
    /**
     * @ORM\Column(type="string", unique=true)
     */
    public string $name;

    public function __construct(UuidInterface $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
