<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreKnowledgeBaseArticle;

use Palach\Omnidesk\DTO\KnowledgeBaseArticleData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseArticle\Response as StoreKnowledgeBaseArticleResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreKnowledgeBaseArticleResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single section article' => [
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

            'expected' => new StoreKnowledgeBaseArticleResponse(
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
                )
            ),
        ];

        yield 'multilingual article' => [
            'data' => [
                'kb_article' => [
                    'article_id' => 101,
                    'section_id' => 12,
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
                    'access_type' => 'staff_only',
                    'active' => false,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Thu, 25 Dec 2014 15:30:00 +0200',
                ],
            ],

            'expected' => new StoreKnowledgeBaseArticleResponse(
                kbArticle: new KnowledgeBaseArticleData(
                    articleId: 101,
                    sectionId: 12,
                    articleTitle: [
                        '1' => 'Название статьи',
                        '2' => 'Test article title',
                    ],
                    articleContent: [
                        '1' => 'Содержание статьи',
                        '2' => 'Test article content',
                    ],
                    accessType: 'staff_only',
                    active: false,
                    createdAt: 'Wed, 15 Jun 2023 14:30:00 +0300',
                    updatedAt: 'Thu, 25 Dec 2014 15:30:00 +0200',
                    articleTags: [
                        '1' => 'тег,тег,тег',
                        '2' => 'tag,tag,tag',
                    ],
                )
            ),
        ];

        yield 'minimal response' => [
            'data' => [
                'kb_article' => [
                    'article_id' => 102,
                    'section_id' => 13,
                    'article_title' => 'Minimal Article',
                    'article_content' => 'Minimal content',
                    'article_tags' => '',
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],

            'expected' => new StoreKnowledgeBaseArticleResponse(
                kbArticle: new KnowledgeBaseArticleData(
                    articleId: 102,
                    sectionId: 13,
                    articleTitle: 'Minimal Article',
                    articleContent: 'Minimal content',
                    accessType: 'public',
                    active: true,
                    createdAt: 'Thu, 20 Jul 2023 09:15:00 +0300',
                    updatedAt: 'Fri, 26 Dec 2014 11:20:00 +0200',
                    articleTags: '',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, StoreKnowledgeBaseArticleResponse $expected): void
    {
        $response = StoreKnowledgeBaseArticleResponse::from($data);

        $this->assertEquals($expected, $response);
    }
}
