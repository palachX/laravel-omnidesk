<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategory;

use Palach\Omnidesk\Traits\HasQueryConversion;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data
{
    use HasQueryConversion;

    public function __construct(
        #[Required]
        public readonly int $categoryId,
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

        if (! $this->languageId instanceof Optional) {
            $query['language_id'] = $this->languageId;
        }

        return $query;
    }
}
