<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class KnowledgeBaseCategoryUpdateData extends Data
{
    /**
     * @param  string|array<string,string>  $categoryTitle
     */
    public function __construct(
        public readonly string|array $categoryTitle
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
