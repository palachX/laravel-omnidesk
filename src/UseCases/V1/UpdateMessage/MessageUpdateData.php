<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\UpdateMessage;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class MessageUpdateData extends Data
{
    public function __construct(
        public readonly string $content,
        public readonly string $contentHtml,
    ) {}
}
