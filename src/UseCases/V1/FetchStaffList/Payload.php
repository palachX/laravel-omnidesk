<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchStaffList;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data
{
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
        public readonly string|Optional $languageId = new Optional,
    ) {}

    /**
     * Serialization to meet the requirements of the external Omnidesk API
     *
     * @return array<mixed>
     */
    public function toQuery(): array
    {
        $query = [];
        $data = $this->toArray();

        foreach ($data as $key => $datum) {
            if ($datum instanceof Optional) {
                continue;
            }

            $query[$key] = $datum;
        }

        return $query;
    }
}
