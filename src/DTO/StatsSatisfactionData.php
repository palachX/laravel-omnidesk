<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class StatsSatisfactionData extends Data
{
    public function __construct(
        public readonly int $ratingId,
        public readonly string $rating,
        public readonly int $ratedStaffId,
        public readonly int $caseId,
        public readonly string $caseNumber,
        public readonly int $userId,
        public readonly int $staffId,
        public readonly int $groupId,
        public readonly string $createdAt,
        public readonly string $updatedAt,
        public readonly string|Optional $ratingComment = new Optional,
    ) {}
}
