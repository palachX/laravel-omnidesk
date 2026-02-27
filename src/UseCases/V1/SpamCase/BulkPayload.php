<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\SpamCase;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class BulkPayload extends Data
{
    /**
     * @param  int[]  $caseIds
     */
    public function __construct(
        public readonly array $caseIds
    ) {
        if (count($this->caseIds) > 10) {
            throw new \InvalidArgumentException('Maximum 10 case IDs allowed per request');
        }
    }
}
