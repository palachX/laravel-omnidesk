<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchKnowledgeBaseArticleList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\KnowledgeBaseArticleData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticleList\Response as FetchKnowledgeBaseArticleListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchKnowledgeBaseArticleListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with multiple articles' => [
            'data' => [
                'kb_articles' => [
                    [
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
                    [
                        'article_id' => 101,
                        'section_id' => 11,
                        'section_id_arr' => [11],
                        'article_title' => 'Test article title 2',
                        'article_content' => 'Test article content 2',
                        'article_tags' => 'tag2,tag2,tag2',
                        'access_type' => 'public',
                        'active' => false,
                        'created_at' => 'Mon, 15 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 13 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total' => 10,
            ],

            'expected' => new FetchKnowledgeBaseArticleListResponse(
                kbArticles: new Collection([
                    new KnowledgeBaseArticleData(
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
                    new KnowledgeBaseArticleData(
                        articleId: 101,
                        sectionId: 11,
                        articleTitle: 'Test article title 2',
                        articleContent: 'Test article content 2',
                        accessType: 'public',
                        active: false,
                        createdAt: 'Mon, 15 May 2014 00:15:17 +0300',
                        updatedAt: 'Tue, 13 Dec 2014 10:55:23 +0200',
                        articleTags: 'tag2,tag2,tag2',
                        sectionIdArr: [11],
                    ),
                ]),
                total: 10
            ),
        ];

        yield 'multilingual articles' => [
            'data' => [
                'kb_articles' => [
                    [
                        'article_id' => 100,
                        'section_id' => 10,
                        'section_id_arr' => [10, 11],
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
                'total' => 5,
            ],

            'expected' => new FetchKnowledgeBaseArticleListResponse(
                kbArticles: new Collection([
                    new KnowledgeBaseArticleData(
                        articleId: 100,
                        sectionId: 10,
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
                        sectionIdArr: [10, 11],
                    ),
                ]),
                total: 5
            ),
        ];

        yield 'empty articles list' => [
            'data' => [
                'kb_articles' => [],
                'total' => 0,
            ],

            'expected' => new FetchKnowledgeBaseArticleListResponse(
                kbArticles: new Collection([]),
                total: 0
            ),
        ];

        yield 'single article' => [
            'data' => [
                'kb_articles' => [
                    [
                        'article_id' => 1,
                        'section_id' => 1,
                        'section_id_arr' => [1],
                        'article_title' => 'Single article',
                        'article_content' => 'Single article content',
                        'article_tags' => 'single',
                        'access_type' => 'public',
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total' => 1,
            ],

            'expected' => new FetchKnowledgeBaseArticleListResponse(
                kbArticles: new Collection([
                    new KnowledgeBaseArticleData(
                        articleId: 1,
                        sectionId: 1,
                        articleTitle: 'Single article',
                        articleContent: 'Single article content',
                        accessType: 'public',
                        active: true,
                        createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                        updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                        articleTags: 'single',
                        sectionIdArr: [1],
                    ),
                ]),
                total: 1
            ),
        ];

        yield 'article without optional fields' => [
            'data' => [
                'kb_articles' => [
                    [
                        'article_id' => 1,
                        'section_id' => 1,
                        'article_title' => 'Article without optional',
                        'article_content' => 'Content without optional',
                        'access_type' => 'public',
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total' => 1,
            ],

            'expected' => new FetchKnowledgeBaseArticleListResponse(
                kbArticles: new Collection([
                    new KnowledgeBaseArticleData(
                        articleId: 1,
                        sectionId: 1,
                        articleTitle: 'Article without optional',
                        articleContent: 'Content without optional',
                        accessType: 'public',
                        active: true,
                        createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                        updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ),
                ]),
                total: 1
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchKnowledgeBaseArticleListResponse $expected): void
    {
        $actual = FetchKnowledgeBaseArticleListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
