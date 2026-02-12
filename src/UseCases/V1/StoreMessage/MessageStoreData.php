<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\StoreMessage;

use Palach\Omnidesk\DTO\AttachmentData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class MessageStoreData extends Data
{
    /**
     * @param  AttachmentData[]|Optional  $attachments
     * @param  string[]|Optional  $attachmentUrls
     */
    public function __construct(
        public readonly string $content,
        public readonly string $contentHtml,
        public readonly int|Optional $staffId = new Optional,
        public readonly int|Optional $userId = new Optional,
        public string|Optional $createdAt = new Optional,
        public readonly array|Optional $attachments = new Optional,
        public readonly array|Optional $attachmentUrls = new Optional,
        public readonly bool|Optional $doNotSendEmail = new Optional,
        public readonly string|Optional $sendAt = new Optional
    ) {}
}
