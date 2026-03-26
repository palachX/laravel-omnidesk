<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchStatsSatisfaction;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\StatsSatisfactionData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, StatsSatisfactionData>  $statsSatisfaction
     */
    public function __construct(
        public readonly Collection $statsSatisfaction,
        public readonly int $totalCount,
    ) {}
}
