<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseSection;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class KnowledgeBaseSectionUpdateData extends Data
{
    /**
     * @param  string|array<string,string>  $sectionTitle
     * @param  string|array<string,string>  $sectionDescription
     */
    public function __construct(
        public readonly string|array $sectionTitle,
        public readonly string|array $sectionDescription,
        public readonly int $categoryId
    ) {}

    /**
     * @return array<mixed>
     */
    public static function rules(): array
    {
        return [
            'section_title' => ['required'],
            'section_description' => ['required'],
            'category_id' => ['required'],
        ];
    }
}
