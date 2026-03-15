<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class MacroCategoryData extends Data
{
    /**
     * @param  Collection<int, MacroData>  $data
     */
    public function __construct(
        public readonly string $title,
        public readonly int $sort,
        public readonly int $macrosCategoryId,
        public readonly Collection $data,
    ) {}
}
