<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\StoreStaff;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data
{
    public function __construct(
        public readonly string $staffEmail,
        public readonly string|Optional $staffFullName = new Optional,
        public readonly string|Optional $staffSignature = new Optional,
        public readonly string|Optional $staffSignatureChat = new Optional,
    ) {}
}
