<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\RateCase;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class RateData extends Data
{
    public function __construct(
        public readonly string $rating,
        public readonly string|Optional $ratingComment = new Optional,
        public readonly int|Optional $ratedStaffId = new Optional,
    ) {}
}
