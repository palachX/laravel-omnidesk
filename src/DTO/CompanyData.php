<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class CompanyData extends Data
{
    public function __construct(
        public readonly int $companyId,
        public readonly string $companyName,
        public readonly string|Optional $companyDomains = new Optional,
        public readonly int|Optional $companyDefaultGroup = new Optional,
        public readonly string|Optional $companyAddress = new Optional,
        public readonly string|Optional $companyNote = new Optional,
        public readonly bool|Optional $active = new Optional,
        public readonly bool|Optional $deleted = new Optional,
        public readonly string|Optional $createdAt = new Optional,
        public readonly string|Optional $updatedAt = new Optional,
    ) {}
}
