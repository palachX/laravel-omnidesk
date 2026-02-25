<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class FilterData extends Data
{
    public function __construct(
        public readonly int $filterId,
        public readonly string $filterName,
        public readonly bool $isSelected,
        public readonly bool $isCustom,
    ) {}
}
