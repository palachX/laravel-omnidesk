<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class AttachmentData extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $mimeType,
        public readonly string $content,
    ) {}
}
