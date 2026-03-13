<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchStaffList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\StaffData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, StaffData>  $staffs
     */
    public function __construct(
        public readonly Collection $staffs,
        public readonly int $total,
    ) {}
}
