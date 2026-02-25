<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class ChangelogData extends Data
{
    public function __construct(
        public readonly string $createdAt,
        public readonly string $event,
        public readonly string $doneBy,
        public readonly string|Optional $oldValue = new Optional,
        public readonly string|Optional $value = new Optional,
    ) {}
}
