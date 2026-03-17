<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class KnowledgeBaseSectionData extends Data
{
    /**
     * @param  string|array<string,string>  $sectionTitle
     * @param  string|array<string,string>|Optional  $sectionDescription
     */
    public function __construct(
        public readonly int $sectionId,
        public readonly int $categoryId,
        public readonly string|array $sectionTitle,
        public readonly bool $active,
        public readonly string $createdAt,
        public readonly string $updatedAt,
        public readonly string|array|Optional $sectionDescription = new Optional,
    ) {}

    /**
     * @return array<mixed>
     */
    public static function rules(): array
    {
        return [
            'section_title' => ['required'],
            'section_description' => [],
        ];
    }
}
