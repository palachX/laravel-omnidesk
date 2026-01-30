<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Http\Requests;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class WebhookRequest extends Data
{
    public function __construct(
        public readonly string $type,
        public readonly mixed $object,
    ) {}
}
