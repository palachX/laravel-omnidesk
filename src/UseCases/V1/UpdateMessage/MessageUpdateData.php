<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\UpdateMessage;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class MessageUpdateData extends Data
{
    public function __construct(
        public readonly int $messageId,
        public readonly string $content,
        #[RequiredWithout('case_number')]
        public readonly int|Optional $caseId = new Optional,
        #[RequiredWithout('case_id')]
        public readonly string|Optional $caseNumber = new Optional,
    ) {}
}
