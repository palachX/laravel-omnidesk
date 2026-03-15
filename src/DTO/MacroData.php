<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class MacroData extends Data
{
    /**
     * @param  Collection<int, MacroActionData>  $actions
     */
    public function __construct(
        public readonly string $title,
        public readonly int $position,
        public readonly Collection $actions,
        public readonly string|Optional $groupName = new Optional,
    ) {}
}
