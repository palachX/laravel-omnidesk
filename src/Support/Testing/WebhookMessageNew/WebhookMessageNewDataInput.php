<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Support\Testing\WebhookMessageNew;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class WebhookMessageNewDataInput extends Data
{
    public function __construct(
        public readonly int $messageId,
        public readonly int $caseId,
        public readonly int $staffId,
        public readonly int $userId,
        public readonly string $customUserId,
        public readonly string $content,
        public readonly bool $note,
        public readonly bool $sentViaRule,
    ) {}
}
