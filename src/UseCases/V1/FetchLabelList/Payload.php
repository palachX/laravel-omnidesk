<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchLabelList;

use Palach\Omnidesk\Traits\HasQueryConversion;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data
{
    use HasQueryConversion;

    public function __construct(
        #[Max(500)]
        #[Min(1)]
        public readonly int $page,
        #[Max(100)]
        #[Min(1)]
        public readonly int $limit,
    ) {}

    /**
     * Serialization to meet the requirements of the external Omnidesk API
     *
     * @return array<mixed>
     */
    public function toQuery(): array
    {
        $query = [];

        $query['page'] = $this->page;
        $query['limit'] = $this->limit;

        return $query;
    }
}
