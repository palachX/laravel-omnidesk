<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\StoreNote;

use Palach\Omnidesk\DTO\AttachmentData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class NoteStoreData extends Data
{
    /**
     * @param  AttachmentData[]|Optional  $attachments
     * @param  string[]|Optional  $attachmentUrls
     */
    public function __construct(
        public readonly string $content,
        public readonly string $contentHtml,
        public readonly int $createdAt,
        public readonly array|Optional $attachments = new Optional,
        public readonly array|Optional $attachmentUrls = new Optional
    ) {}
}
