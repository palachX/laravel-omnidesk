<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\UpdateLabel;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class LabelUpdateData extends Data
{
    public function __construct(
        public readonly string|Optional $labelTitle = new Optional,
    ) {}
}
