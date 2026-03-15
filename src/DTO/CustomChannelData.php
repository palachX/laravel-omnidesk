<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class CustomChannelData extends Data
{
    public function __construct(
        public readonly int $channelId,
        public readonly string $channelApiKey,
        public readonly string $title,
        public readonly string $channelType,
        public readonly string $icon,
        public readonly string $webhookUrl,
        public readonly bool $active,
    ) {}
}
