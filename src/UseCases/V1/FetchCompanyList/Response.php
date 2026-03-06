<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchCompanyList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\CompanyData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, CompanyData>  $companies
     */
    public function __construct(
        public readonly Collection $companies,
        public readonly int $total,
    ) {}
}
