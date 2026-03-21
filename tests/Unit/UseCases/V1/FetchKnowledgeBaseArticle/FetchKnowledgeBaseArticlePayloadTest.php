<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchKnowledgeBaseArticle;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticle\Payload as FetchKnowledgeBaseArticlePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchKnowledgeBaseArticlePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'valid article id' => [
            'data' => [
                'article_id' => 100,
            ],
            'expected' => new FetchKnowledgeBaseArticlePayload(
                articleId: 100,
            ),
        ];

        yield 'article id with language' => [
            'data' => [
                'article_id' => 200,
                'language_id' => '2',
            ],
            'expected' => new FetchKnowledgeBaseArticlePayload(
                articleId: 200,
                languageId: '2',
            ),
        ];

        yield 'article id with all languages' => [
            'data' => [
                'article_id' => 300,
                'language_id' => 'all',
            ],
            'expected' => new FetchKnowledgeBaseArticlePayload(
                articleId: 300,
                languageId: 'all',
            ),
        ];

        yield 'minimum article id' => [
            'data' => [
                'article_id' => 1,
            ],
            'expected' => new FetchKnowledgeBaseArticlePayload(
                articleId: 1,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchKnowledgeBaseArticlePayload $expected): void
    {
        $actual = FetchKnowledgeBaseArticlePayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
