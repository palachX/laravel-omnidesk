<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class OmnideskConfig extends Data
{
    /**
     * @param  Collection<int, ConfigWebhook>  $webhooks
     */
    public function __construct(
        public readonly string $email,
        public readonly string $apiKey,
        public readonly string $host,
        public readonly Collection $webhooks
    ) {}
}
