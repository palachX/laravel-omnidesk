<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticleList;

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
         * Page number (positive integer)
         */
        #[Max(500)]
        #[Min(1)]
        public readonly int|Optional $page = new Optional,
        /**
         * Limit of articles per page (integer from 1 to 100)
         */
        #[Max(100)]
        #[Min(1)]
        public readonly int|Optional $limit = new Optional,
        /**
         * Search string (minimum 3 characters if used)
         */
        public readonly string|Optional $search = new Optional,
        /**
         * Section ID to get articles only from specific section
         */
        public readonly string|Optional $sectionId = new Optional,
        /**
         * Default in omnidesk primary language
         * Can be "all" to get all languages
         */
        public readonly string|Optional $languageId = new Optional,
        /**
         * Sort order
         * Available values: id_desc, id_asc, created_at_desc, created_at_asc, manual_order
         */
        public readonly string|Optional $sort = new Optional,
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
