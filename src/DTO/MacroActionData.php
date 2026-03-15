<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class MacroActionData extends Data
{
    public function __construct(
        public readonly int $macroActionId,
        public readonly string $actionType,
        public readonly string $actionDisplayName,
        public readonly mixed $actionDestination,
        public readonly int $position,
        public readonly string|Optional $content = new Optional,
        public readonly string|Optional $subject = new Optional,
    ) {}
}
