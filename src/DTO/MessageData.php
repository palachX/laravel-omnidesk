<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
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
        public bool $note,
        public string $createdAt,
        #[RequiredWithout('content_html')]
        public readonly string|Optional $content = new Optional,
        #[RequiredWithout('content')]
        public readonly string|Optional $contentHtml = new Optional,
        public readonly array|Optional $attachments = new Optional,
        public readonly string|Optional $rating = new Optional,
        public readonly string|Optional $ratingComment = new Optional,
        public readonly int|Optional $ratedStaffId = new Optional,
        public readonly bool|Optional $sentViaRule = new Optional,
        public readonly string|Optional $sentAt = new Optional,
    ) {}
}
