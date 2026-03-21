<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchKnowledgeBaseArticle;

use Palach\Omnidesk\DTO\KnowledgeBaseArticleData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticle\Response as FetchKnowledgeBaseArticleResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchKnowledgeBaseArticleResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full article data with single language' => [
            'data' => [
                'kb_article' => [
                    'article_id' => 100,
                    'section_id' => 10,
                    'section_id_arr' => [10, 11],
                    'article_title' => 'Test article title',
                    'article_content' => 'Test article content',
                    'article_tags' => 'tag,tag,tag',
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new FetchKnowledgeBaseArticleResponse(
                kbArticle: new KnowledgeBaseArticleData(
                    articleId: 100,
                    sectionId: 10,
                    articleTitle: 'Test article title',
                    articleContent: 'Test article content',
                    accessType: 'public',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    articleTags: 'tag,tag,tag',
                    sectionIdArr: [10, 11],
                ),
            ),
        ];

        yield 'full article data with multiple languages' => [
            'data' => [
                'kb_article' => [
                    'article_id' => 200,
                    'section_id' => 20,
                    'section_id_arr' => [20, 21],
                    'article_title' => [
                        '1' => 'Название статьи',
                        '2' => 'Test article title',
                    ],
                    'article_content' => [
                        '1' => 'Содержание статьи',
                        '2' => 'Test article content',
                    ],
                    'article_tags' => [
                        '1' => 'тег,тег,тег',
                        '2' => 'tag,tag,tag',
                    ],
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new FetchKnowledgeBaseArticleResponse(
                kbArticle: new KnowledgeBaseArticleData(
                    articleId: 200,
                    sectionId: 20,
                    articleTitle: [
                        '1' => 'Название статьи',
                        '2' => 'Test article title',
                    ],
                    articleContent: [
                        '1' => 'Содержание статьи',
                        '2' => 'Test article content',
                    ],
                    accessType: 'public',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    articleTags: [
                        '1' => 'тег,тег,тег',
                        '2' => 'tag,tag,tag',
                    ],
                    sectionIdArr: [20, 21],
                ),
            ),
        ];

        yield 'minimal article data' => [
            'data' => [
                'kb_article' => [
                    'article_id' => 300,
                    'section_id' => 30,
                    'article_title' => 'Simple article',
                    'article_content' => 'Simple article content',
                    'access_type' => 'private',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new FetchKnowledgeBaseArticleResponse(
                kbArticle: new KnowledgeBaseArticleData(
                    articleId: 300,
                    sectionId: 30,
                    articleTitle: 'Simple article',
                    articleContent: 'Simple article content',
                    accessType: 'private',
                    active: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchKnowledgeBaseArticleResponse $expected): void
    {
        $actual = FetchKnowledgeBaseArticleResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
