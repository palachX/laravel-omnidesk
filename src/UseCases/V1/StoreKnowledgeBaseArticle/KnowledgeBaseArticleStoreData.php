<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseArticle;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class KnowledgeBaseArticleStoreData extends Data
{
    /**
     * @param  string|array<string,string>  $articleTitle
     * @param  string|array<string,string>  $articleContent
     * @param  string|array<string,string>|Optional  $articleTags
     * @param  string|array<string,string>  $sectionId
     */
    public function __construct(
        public readonly string|array $articleTitle,
        public readonly string|array $articleContent,
        public readonly string|array $sectionId,
        public readonly string|array|Optional $articleTags = new Optional,
        public readonly string|Optional $accessType = new Optional,
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
            'section_id' => ['required'],
            'access_type' => [],
        ];
    }
}
