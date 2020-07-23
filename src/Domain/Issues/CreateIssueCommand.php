<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use Symfony\Component\HttpFoundation\Request;

/**
 * @psalm-immutable
 */
final class CreateIssueCommand
{
    public string $title;
    public array $tagIds;

    public function __construct(string $title, array $tagIds)
    {
        $this->title = $title;
        $this->tagIds = $tagIds;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'],
            $data['tags']
        );
    }
}
