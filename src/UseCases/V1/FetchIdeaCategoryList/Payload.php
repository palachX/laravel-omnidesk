<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchIdeaCategoryList;

use Palach\Omnidesk\Traits\HasQueryConversion;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data
{
    use HasQueryConversion;

    public function __construct(
        /**
         * Default in omnidesk 1
         */
        #[Max(500)]
        #[Min(1)]
        public readonly int|Optional $page = new Optional,

        /**
         * Default in omnidesk 100
         */
        #[Max(100)]
        #[Min(1)]
        public readonly int|Optional $limit = new Optional,
    ) {}

    /**
     * Serialization to meet the requirements of the external Omnidesk API
     *
     * @return array<mixed>
     */
    public function toQuery(): array
    {
        $query = [];

        if (! $this->page instanceof Optional) {
            $query['page'] = $this->page;
        }

        if (! $this->limit instanceof Optional) {
            $query['limit'] = $this->limit;
        }

        return $query;
    }
}
