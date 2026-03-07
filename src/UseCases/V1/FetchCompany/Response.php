<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchCompany;

use Palach\Omnidesk\DTO\CompanyData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    public function __construct(
        public readonly CompanyData $company,
    ) {}
}
