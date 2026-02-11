<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class FileData extends Data
{
    public function __construct(
        public readonly int $fileId,
        public readonly string $fileName,
        public readonly int $fileSize,
        public readonly string $mimeType,
        public readonly string $url,
    ) {}
}
