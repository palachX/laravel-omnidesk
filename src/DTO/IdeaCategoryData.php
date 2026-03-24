<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class IdeaCategoryData extends Data
{
    public function __construct(
        public readonly int $categoryId,
        public readonly string $categoryTitle,
        public readonly bool $active,
        public readonly int|Optional $categoryDefaultGroup = new Optional,
    ) {}
}
