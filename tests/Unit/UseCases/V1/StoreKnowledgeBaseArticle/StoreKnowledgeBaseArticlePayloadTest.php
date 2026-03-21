<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreKnowledgeBaseArticle;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseArticle\KnowledgeBaseArticleStoreData;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseArticle\Payload as StoreKnowledgeBaseArticlePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreKnowledgeBaseArticlePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'string title and content' => [
            'data' => [
                'kb_article' => [
                    'article_title' => 'Test Article Stored',
                    'article_content' => 'Test article content',
                    'article_tags' => 'test,article,content',
                    'section_id' => ['10', '11'],
                    'access_type' => 'public',
                ],
            ],

            'expected' => new StoreKnowledgeBaseArticlePayload(
                kbArticle: new KnowledgeBaseArticleStoreData(
                    articleTitle: 'Test Article Stored',
                    articleContent: 'Test article content',
                    articleTags: 'test,article,content',
                    sectionId: ['10', '11'],
                    accessType: 'public',
                )
            ),
        ];

        yield 'multilingual title and content' => [
            'data' => [
                'kb_article' => [
                    'article_title' => [
                        '1' => 'Тестовая статья',
                        '2' => 'Test Article',
                    ],
                    'article_content' => [
                        '1' => 'Содержание тестовой статьи',
                        '2' => 'Test article content',
                    ],
                    'article_tags' => [
                        '1' => 'тег,статья,содержание',
                        '2' => 'tag,article,content',
                    ],
                    'section_id' => ['10', '11'],
                    'access_type' => 'staff_only',
                ],
            ],

            'expected' => new StoreKnowledgeBaseArticlePayload(
                kbArticle: new KnowledgeBaseArticleStoreData(
                    articleTitle: [
                        '1' => 'Тестовая статья',
                        '2' => 'Test Article',
                    ],
                    articleContent: [
                        '1' => 'Содержание тестовой статьи',
                        '2' => 'Test article content',
                    ],
                    articleTags: [
                        '1' => 'тег,статья,содержание',
                        '2' => 'tag,article,content',
                    ],
                    sectionId: ['10', '11'],
                    accessType: 'staff_only',
                )
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'kb_article' => [
                    'article_title' => 'Minimal Article',
                    'article_content' => 'Minimal content',
                    'section_id' => '10',
                ],
            ],

            'expected' => new StoreKnowledgeBaseArticlePayload(
                kbArticle: new KnowledgeBaseArticleStoreData(
                    articleTitle: 'Minimal Article',
                    articleContent: 'Minimal content',
                    sectionId: '10',
                )
            ),
        ];

        yield 'single section with tags' => [
            'data' => [
                'kb_article' => [
                    'article_title' => 'Single Section Article',
                    'article_content' => 'Article with single section',
                    'article_tags' => ['single', 'section'],
                    'section_id' => '15',
                ],
            ],

            'expected' => new StoreKnowledgeBaseArticlePayload(
                kbArticle: new KnowledgeBaseArticleStoreData(
                    articleTitle: 'Single Section Article',
                    articleContent: 'Article with single section',
                    sectionId: '15',
                    articleTags: ['single', 'section'],
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, StoreKnowledgeBaseArticlePayload $expected): void
    {
        $payload = StoreKnowledgeBaseArticlePayload::validateAndCreate($data);

        $this->assertEquals($expected, $payload);
    }
}
