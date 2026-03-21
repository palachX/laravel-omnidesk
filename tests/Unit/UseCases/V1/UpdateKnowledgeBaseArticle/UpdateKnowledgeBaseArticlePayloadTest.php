<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateKnowledgeBaseArticle;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseArticle\KnowledgeBaseArticleUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseArticle\Payload as UpdateKnowledgeBaseArticlePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateKnowledgeBaseArticlePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'string title and content' => [
            'data' => [
                'kb_article' => [
                    'article_title' => 'Test Article Updated',
                    'article_content' => 'Test article content updated',
                    'article_tags' => 'tag,updated',
                    'section_id' => 20,
                ],
            ],

            'expected' => new UpdateKnowledgeBaseArticlePayload(
                kbArticle: new KnowledgeBaseArticleUpdateData(
                    articleTitle: 'Test Article Updated',
                    articleContent: 'Test article content updated',
                    articleTags: 'tag,updated',
                    sectionId: 20,
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
                        '1' => 'Тестовое содержание статьи',
                        '2' => 'Test article content',
                    ],
                    'article_tags' => [
                        '1' => 'тег,обновленный',
                        '2' => 'tag,updated',
                    ],
                    'section_id' => 20,
                ],
            ],

            'expected' => new UpdateKnowledgeBaseArticlePayload(
                kbArticle: new KnowledgeBaseArticleUpdateData(
                    articleTitle: [
                        '1' => 'Тестовая статья',
                        '2' => 'Test Article',
                    ],
                    articleContent: [
                        '1' => 'Тестовое содержание статьи',
                        '2' => 'Test article content',
                    ],
                    articleTags: [
                        '1' => 'тег,обновленный',
                        '2' => 'tag,updated',
                    ],
                    sectionId: 20,
                )
            ),
        ];

        yield 'with access type' => [
            'data' => [
                'kb_article' => [
                    'article_title' => 'Staff Only Article',
                    'article_content' => 'Staff only content',
                    'article_tags' => 'staff,internal',
                    'section_id' => 25,
                    'access_type' => 'staff_only',
                ],
            ],

            'expected' => new UpdateKnowledgeBaseArticlePayload(
                kbArticle: new KnowledgeBaseArticleUpdateData(
                    articleTitle: 'Staff Only Article',
                    articleContent: 'Staff only content',
                    articleTags: 'staff,internal',
                    sectionId: 25,
                    accessType: 'staff_only',
                )
            ),
        ];

        yield 'array section_id' => [
            'data' => [
                'kb_article' => [
                    'article_title' => 'Multi Section Article',
                    'article_content' => 'Content for multiple sections',
                    'article_tags' => 'multi,section',
                    'section_id' => [20, 21, 22],
                ],
            ],

            'expected' => new UpdateKnowledgeBaseArticlePayload(
                kbArticle: new KnowledgeBaseArticleUpdateData(
                    articleTitle: 'Multi Section Article',
                    articleContent: 'Content for multiple sections',
                    articleTags: 'multi,section',
                    sectionId: [20, 21, 22],
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testValidateAndCreate(array $data, UpdateKnowledgeBaseArticlePayload $expected): void
    {
        $actual = UpdateKnowledgeBaseArticlePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
