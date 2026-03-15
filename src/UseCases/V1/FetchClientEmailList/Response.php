<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchClientEmailList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\ClientEmailData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, ClientEmailData>  $clientEmails
     */
    public function __construct(
        public readonly Collection $clientEmails,
        public readonly int $totalCount,
    ) {}
}
