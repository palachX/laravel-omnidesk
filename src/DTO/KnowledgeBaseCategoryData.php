<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class KnowledgeBaseCategoryData extends Data
{
    /**
     * @param  string|array<string,string>  $category_title
     */
    public function __construct(
        public readonly int $categoryId,
        public readonly string|array $category_title,
        public readonly bool $active,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}
}
