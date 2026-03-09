<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class GroupData extends Data
{
    public function __construct(
        public readonly int $groupId,
        public readonly string $groupTitle,
        public readonly string|Optional $groupFromName = new Optional,
        public readonly string|Optional $groupSignature = new Optional,
        public readonly bool|Optional $active = new Optional,
        public readonly string|Optional $createdAt = new Optional,
        public readonly string|Optional $updatedAt = new Optional,
    ) {}
}
