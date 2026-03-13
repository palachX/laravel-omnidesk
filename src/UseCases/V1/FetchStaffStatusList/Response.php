<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchStaffStatusList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\StaffStatusData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, StaffStatusData>  $staffStatuses
     */
    public function __construct(
        public readonly Collection $staffStatuses,
        public readonly int $count,
    ) {}
}
