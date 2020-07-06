<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

final class IssueTransformer extends TransformerAbstract
{
    /** @var string[] */
    protected $availableIncludes = [
        'votes',
        'tags'
    ];

    /** @var string[] */
    protected $defaultIncludes = [
        'votes',
        'tags'
    ];
    
    /**
     * @return array<string, mixed>
     */
    public function transform(Issue $issue): array
    {
        return [
            'id' => $issue->getId()->toString(),
            'title' => $issue->getTitle(),
            'createdAt' => $issue->getCreatedAt()->format(DATE_ATOM),
            'author' => $issue->getAuthorId()->toString()
        ];
    }

    public function includeVotes(Issue $issue): Collection
    {
        $votes = $issue->getVotes();

        return new Collection($votes, new VoteTransformer());
    }

    public function includeTags(Issue $issue): Collection
    {
        $tags = $issue->getTags();

        return new Collection($tags, new TagTransformer());
    }
}
