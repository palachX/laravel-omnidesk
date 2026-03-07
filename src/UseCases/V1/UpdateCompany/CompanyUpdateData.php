<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\UpdateCompany;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class CompanyUpdateData extends Data
{
    public function __construct(
        public readonly string|Optional $companyName = new Optional,
        public readonly string|Optional $addCompanyDomains = new Optional,
        public readonly string|Optional $removeCompanyDomains = new Optional,
        public readonly string|Optional $companyDefaultGroup = new Optional,
        public readonly string|Optional $companyAddress = new Optional,
        public readonly string|Optional $companyNote = new Optional,
        public readonly string|Optional $addCompanyUsers = new Optional,
        public readonly string|Optional $removeCompanyUsers = new Optional
    ) {}
}
