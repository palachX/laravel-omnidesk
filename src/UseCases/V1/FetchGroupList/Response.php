<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchGroupList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\GroupData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, GroupData>  $groups
     */
    public function __construct(
        public readonly Collection $groups,
        public readonly int $total,
    ) {}
}
