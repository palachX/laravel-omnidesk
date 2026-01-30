<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class CaseData extends Data
{
    public function __construct(
        public readonly int $caseId,
        public readonly string $caseNumber,
        public readonly string $subject,
        public readonly int $userId,
        public readonly int $staffId,
        public readonly int $groupId,
        public readonly string $status,
        public readonly string $priority,
        public readonly string $channel,
        public readonly bool $deleted,
        public readonly bool $spam,
    ) {}
}
