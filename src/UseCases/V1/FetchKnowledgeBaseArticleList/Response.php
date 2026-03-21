<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticleList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\KnowledgeBaseArticleData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, KnowledgeBaseArticleData>  $kbArticles
     */
    public function __construct(
        public readonly Collection $kbArticles,
        public readonly int $total,
    ) {}
}
