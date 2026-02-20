<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\RestoreCase;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class BulkResponse extends Data
{
    /**
     * @param  int[]  $caseSuccessId
     */
    public function __construct(
        public readonly array $caseSuccessId
    ) {}
}
