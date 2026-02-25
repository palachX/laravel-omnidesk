<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class MessageData extends Data
{
    /**
     * @param  FileData[]|Optional  $attachments
     */
    public function __construct(
        public readonly int $messageId,
        public readonly int $userId,
        public readonly int $staffId,
        public readonly string $content,
        public bool $note,
        public string $createdAt,
        public readonly string|Optional $contentHtml = new Optional,
        public readonly array|Optional $attachments = new Optional,
        public readonly string|Optional $rating = new Optional,
        public readonly string|Optional $ratingComment = new Optional,
        public readonly int|Optional $ratedStaffId = new Optional,
    ) {}
}
