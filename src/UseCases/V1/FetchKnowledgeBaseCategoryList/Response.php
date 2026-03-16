<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, KnowledgeBaseCategoryData>  $kbCategories
     */
    public function __construct(
        public readonly Collection $kbCategories,
        public readonly int $total,
    ) {}
}
