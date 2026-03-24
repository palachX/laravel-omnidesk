<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchIdeaCategoryList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\IdeaCategoryData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, IdeaCategoryData>  $ideaCategories
     */
    public function __construct(
        public readonly Collection $ideaCategories,
        public readonly int $total,
    ) {}
}
