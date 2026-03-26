<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class StatsLeaderboardData extends Data
{
    public function __construct(
        public readonly int $staffId,
        public readonly string $staffName,
        public readonly int $newCasesInTotal,
        public readonly int $newUserCases,
        public readonly int $reopenedCases,
        public readonly int $casesBeingHandled,
        public readonly int $casesWithAResponse,
        public readonly int $firstResponseTime,
        public readonly string $firstResponseSlaViolated,
        public readonly int $responseTime,
        public readonly string $responseSlaViolated,
        public readonly int $responseWritingTime,
        public readonly int $totalNumberOfResponses,
        public readonly int $totalNumberOfNotes,
        public readonly int $numberOfResponsesForResolution,
        public readonly int $closedCases,
        public readonly int $resolutionTime,
        public readonly string $resolutionSlaViolated,
        public readonly string $ratingsOfResponses,
    ) {}
}
