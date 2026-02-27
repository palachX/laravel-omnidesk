<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchCaseMessages;

use Palach\Omnidesk\Traits\HasQueryConversion;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\In;
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
         * Case ID - required parameter
         */
        public readonly int $caseId,
        /**
         * Page number (positive integer)
         */
        #[Min(1)]
        public readonly int|Optional $page = new Optional,
        /**
         * Limit messages per page (integer from 1 to 100)
         */
        #[Max(100)]
        #[Min(1)]
        public readonly int|Optional $limit = new Optional,
        /**
         * Message sort order. Possible values: asc, desc. Default asc (from old to new messages)
         */
        #[In(['asc', 'desc'])]
        public readonly string|Optional $order = new Optional,
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

        if (! $this->order instanceof Optional) {
            $query['order'] = $this->order;
        }

        return $query;
    }
}
