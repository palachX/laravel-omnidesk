<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class IdeaData extends Data
{
    public function __construct(
        public readonly string|Optional $content = new Optional,
        public readonly string|Optional $stage = new Optional,
        public readonly int|Optional $category = new Optional,
        public readonly string|Optional $officialResponse = new Optional,
        public readonly int|Optional $officialResponseTstamp = new Optional,
    ) {}
}
