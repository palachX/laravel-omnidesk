<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseArticle;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class KnowledgeBaseArticleUpdateData extends Data
{
    /**
     * @param  string|array<string,string>  $articleTitle
     * @param  string|array<string,string>  $articleContent
     * @param  string|array<string,string>  $articleTags
     * @param  string|array<string,int>  $sectionId
     */
    public function __construct(
        public readonly string|array $articleTitle,
        public readonly string|array $articleContent,
        public readonly string|array $articleTags,
        public readonly string|array|int $sectionId,
        public readonly ?string $accessType = null
    ) {}

    /**
     * @return array<mixed>
     */
    public static function rules(): array
    {
        return [
            'article_title' => ['required'],
            'article_content' => ['required'],
            'article_tags' => ['required'],
            'section_id' => ['required'],
            'access_type' => ['sometimes', 'in:public,staff_only'],
        ];
    }
}
