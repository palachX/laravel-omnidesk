<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\UpdateNote;

use Palach\Omnidesk\Traits\HasMultipartConversion;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data
{
    use HasMultipartConversion;

    public function __construct(
        public readonly int $caseId,
        public readonly int $messageId,
        public readonly NoteUpdateData $note,
    ) {}
}
