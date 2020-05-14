<?php
declare(strict_types=1);

namespace App\Security;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table
 */
final class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $id;
    /**
     * @ORM\Column(type="string")
     */
    private string $firstName;
    /**
     * @ORM\Column(type="string")
     */
    private string $lastName;
    /**
     * @ORM\Column(type="string", unique=true)
     */
    private string $username;
    /**
     * @ORM\Column(type="string")
     */
    private string $password;

    private function __construct(UuidInterface $id, string $username, string $hashedPassword)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $hashedPassword;
    }

    public static function new(UuidInterface $id, string $username, string $hashedPassword): self
    {
        return new static($id, $username, $hashedPassword);
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function eraseCredentials(): void
    {
        $this->password = '';
    }
}
