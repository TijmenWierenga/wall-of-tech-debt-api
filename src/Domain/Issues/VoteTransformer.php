<?php

declare(strict_types=1);

namespace App\Domain\Issues;

use League\Fractal\TransformerAbstract;

final class VoteTransformer extends TransformerAbstract
{
    /**
     * @return array<string, mixed>
     */
    public function transform(Vote $vote): array
    {
        return [
            'by' => $vote->getUserId()->toString(),
            'amount' => $vote->getAmount()
        ];
    }
}
