<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\StoreMessage;

use Palach\Omnidesk\DTO\AttachmentData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class MessageStoreData extends Data
{
    /**
     * @param  int|Optional  $caseId
     * @param  string|Optional  $caseNumber
     * @param  AttachmentData[]|Optional  $attachments
     * @param  string[]|Optional  $attachmentUrls
     */
    public function __construct(
        public readonly int $userId,
        public readonly string $content,
        #[RequiredWithout('case_number')]
        public readonly int|Optional $caseId = new Optional,
        #[RequiredWithout('case_id')]
        public readonly string|Optional $caseNumber = new Optional,
        public readonly array|Optional $attachments = new Optional,
        public readonly array|Optional $attachmentUrls = new Optional
    ) {}
}
