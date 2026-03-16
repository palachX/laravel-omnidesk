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
     * @param  string|array<string,string>  $categoryTitle
     */
    public function __construct(
        public readonly int $categoryId,
        public readonly string|array $categoryTitle,
        public readonly bool $active,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}

    /**
     * @return array<mixed>
     */
    public static function rules(): array
    {
        return [
            'category_title' => ['required'],
        ];
    }
}
