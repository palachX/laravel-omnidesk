<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateKnowledgeBaseArticle;

use Palach\Omnidesk\DTO\KnowledgeBaseArticleData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseArticle\Response as UpdateKnowledgeBaseArticleResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateKnowledgeBaseArticleResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'string title and content' => [
            'data' => [
                'kb_article' => [
                    'article_id' => 100,
                    'section_id' => 20,
                    'article_title' => 'Test article title 2',
                    'article_content' => 'Test article content 2',
                    'article_tags' => 'tag,tag_new',
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new UpdateKnowledgeBaseArticleResponse(
                kbArticle: new KnowledgeBaseArticleData(
                    articleId: 100,
                    sectionId: 20,
                    articleTitle: 'Test article title 2',
                    articleContent: 'Test article content 2',
                    accessType: 'public',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    articleTags: 'tag,tag_new',
                )
            ),
        ];

        yield 'multilingual title and content' => [
            'data' => [
                'kb_article' => [
                    'article_id' => 101,
                    'section_id' => 20,
                    'article_title' => [
                        '1' => 'Название статьи 2',
                        '2' => 'Test article title 2',
                    ],
                    'article_content' => [
                        '1' => 'Содержание статьи 2',
                        '2' => 'Test article content 2',
                    ],
                    'article_tags' => [
                        '1' => 'тег,тег',
                        '2' => 'tag,tag',
                    ],
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new UpdateKnowledgeBaseArticleResponse(
                kbArticle: new KnowledgeBaseArticleData(
                    articleId: 101,
                    sectionId: 20,
                    articleTitle: [
                        '1' => 'Название статьи 2',
                        '2' => 'Test article title 2',
                    ],
                    articleContent: [
                        '1' => 'Содержание статьи 2',
                        '2' => 'Test article content 2',
                    ],
                    accessType: 'public',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    articleTags: [
                        '1' => 'тег,тег',
                        '2' => 'tag,tag',
                    ],
                )
            ),
        ];

        yield 'staff only access' => [
            'data' => [
                'kb_article' => [
                    'article_id' => 102,
                    'section_id' => 25,
                    'article_title' => 'Updated Article Title',
                    'article_content' => 'Updated article content',
                    'article_tags' => 'updated,tag',
                    'access_type' => 'staff_only',
                    'active' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],

            'expected' => new UpdateKnowledgeBaseArticleResponse(
                kbArticle: new KnowledgeBaseArticleData(
                    articleId: 102,
                    sectionId: 25,
                    articleTitle: 'Updated Article Title',
                    articleContent: 'Updated article content',
                    accessType: 'staff_only',
                    active: false,
                    createdAt: 'Thu, 20 Jul 2023 09:15:00 +0300',
                    updatedAt: 'Fri, 26 Dec 2014 11:20:00 +0200',
                    articleTags: 'updated,tag',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testValidateAndCreate(array $data, UpdateKnowledgeBaseArticleResponse $expected): void
    {
        $actual = UpdateKnowledgeBaseArticleResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
