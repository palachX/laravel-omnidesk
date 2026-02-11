<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\UpdateMessage;

use InvalidArgumentException;
use LogicException;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data
{
    public function __construct(
        public readonly MessageUpdateData $message
    ) {}

    public function getCaseIdentifier(): int|string
    {
        return match (true) {
            $this->message->caseId instanceof Optional && $this->message->caseNumber instanceof Optional => throw new InvalidArgumentException('Not set caseId or CaseNumber'),
            ! ($this->message->caseId instanceof Optional) => $this->message->caseId,
            ! ($this->message->caseNumber instanceof Optional) => $this->message->caseNumber,
            default => throw new LogicException('Both caseId and caseNumber are set'),
        };
    }
}
