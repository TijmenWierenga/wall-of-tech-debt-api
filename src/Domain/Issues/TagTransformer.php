<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use League\Fractal\TransformerAbstract;

final class TagTransformer extends TransformerAbstract
{
    /**
     * @return array<string, mixed>
     */
    public function transform(Tag $tag): array
    {
        return [
            'id' => $tag->getId()->toString(),
            'name' => $tag->getName()
        ];
    }
}
