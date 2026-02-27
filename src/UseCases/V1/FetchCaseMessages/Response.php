<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchCaseMessages;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\MessageData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, MessageData>  $messages
     */
    public function __construct(
        public readonly Collection $messages,
        public readonly int $totalCount,
    ) {}
}
