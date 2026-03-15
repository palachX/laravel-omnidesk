<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchCustomFieldList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\CustomFieldData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, CustomFieldData>  $customFields
     */
    public function __construct(
        public readonly Collection $customFields,
        public readonly int $totalCount,
    ) {}
}
