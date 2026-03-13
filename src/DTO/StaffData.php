<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class StaffData extends Data
{
    public function __construct(
        public readonly int $staffId,
        public readonly string $staffEmail,
        public readonly string|Optional $staffFullName = new Optional,
        public readonly string|Optional $staffSignature = new Optional,
        public readonly string|Optional $staffSignatureChat = new Optional,
        public readonly string|Optional $thumbnail = new Optional,
        public readonly bool|Optional $active = new Optional,
        public readonly string|Optional $status = new Optional,
        public readonly int|Optional $statusId = new Optional,
        public readonly string|Optional $createdAt = new Optional,
        public readonly string|Optional $updatedAt = new Optional,
    ) {}
}
