<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchFilterList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\FilterData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, FilterData>  $filters
     */
    public function __construct(
        public readonly Collection $filters,
        public readonly int $totalCount,
    ) {}
}
