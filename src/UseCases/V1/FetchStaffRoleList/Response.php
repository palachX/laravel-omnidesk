<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchStaffRoleList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\StaffRoleData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, StaffRoleData>  $staffRoles
     */
    public function __construct(
        public readonly Collection $staffRoles,
        public readonly int $count,
    ) {}
}
