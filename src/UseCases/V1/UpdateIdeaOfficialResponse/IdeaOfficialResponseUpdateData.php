<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\UpdateIdeaOfficialResponse;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class IdeaOfficialResponseUpdateData extends Data
{
    public function __construct(
        public readonly string $content,
    ) {}
}
