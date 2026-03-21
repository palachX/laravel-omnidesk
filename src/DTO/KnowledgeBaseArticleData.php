<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class KnowledgeBaseArticleData extends Data
{
    /**
     * @param  string|array<string,string>  $articleTitle
     * @param  string|array<string,string>  $articleContent
     * @param  string|array<string,string>|Optional  $articleTags
     * @param  array<int>|Optional  $sectionIdArr
     */
    public function __construct(
        public readonly int $articleId,
        public readonly int $sectionId,
        public readonly string|array $articleTitle,
        public readonly string|array $articleContent,
        public readonly string $accessType,
        public readonly bool $active,
        public readonly string $createdAt,
        public readonly string $updatedAt,
        public readonly string|array|Optional $articleTags = new Optional,
        public readonly array|Optional $sectionIdArr = new Optional,
    ) {}

    /**
     * @return array<mixed>
     */
    public static function rules(): array
    {
        return [
            'article_title' => ['required'],
            'article_content' => ['required'],
            'article_tags' => [],
        ];
    }
}
