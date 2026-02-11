<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\StoreCase;

use Palach\Omnidesk\DTO\AttachmentData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class CaseStoreData extends Data
{
    /**
     * @param  string|Optional  $userEmail
     * @param  string|Optional  $userPhone
     * @param  AttachmentData[]|Optional  $attachments
     * @param  string[]|Optional  $attachmentUrls
     * */
    public function __construct(
        public readonly string $userCustomId,
        public readonly string $subject,
        public readonly string $content,
        public readonly string $contentHtml,
        public readonly string $channel,
        #[RequiredWithout('user_phone')]
        public readonly string|Optional $userEmail = new Optional,
        #[RequiredWithout('user_email')]
        public readonly string|Optional $userPhone = new Optional,
        public readonly array|Optional $attachments = new Optional,
        public readonly array|Optional $attachmentUrls = new Optional
    ) {}
}
