<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class CaseData extends Data
{
    /**
     * @param  array<string, string|int>|Optional  $customFields
     * @param  array<int>|Optional  $labels
     * @param  array<int>|Optional  $lockedLabels
     * @param  FileData[]|Optional  $attachments
     */
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
        public readonly string|Optional $recipient = new Optional,
        public readonly string|Optional $ccEmails = new Optional,
        public readonly string|Optional $bccEmails = new Optional,
        public readonly bool $deleted = false,
        public readonly bool $spam = false,
        public readonly string|Optional $createdAt = new Optional,
        public readonly string|Optional $closedAt = new Optional,
        public readonly string|Optional $updatedAt = new Optional,
        public readonly string|Optional $lastResponseAt = new Optional,
        public readonly string|Optional $closingSpeed = new Optional,
        public readonly int|Optional $languageId = new Optional,
        public readonly array|Optional $customFields = new Optional,
        public readonly array|Optional $labels = new Optional,
        public readonly array|Optional $lockedLabels = new Optional,
        public readonly string|Optional $rating = new Optional,
        public readonly string|Optional $ratingComment = new Optional,
        public readonly int|Optional $ratedStaffId = new Optional,
        public readonly array|Optional $attachments = new Optional
    ) {}
}
