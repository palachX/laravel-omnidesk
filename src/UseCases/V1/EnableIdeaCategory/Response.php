<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\EnableIdeaCategory;

use Palach\Omnidesk\DTO\IdeaCategoryData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    public function __construct(
        public readonly IdeaCategoryData $ideasCategory,
    ) {}
}
