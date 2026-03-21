<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisabledKnowledgeBaseArticle;

use Palach\Omnidesk\DTO\KnowledgeBaseArticleData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisabledKnowledgeBaseArticle\Response as DisabledKnowledgeBaseArticleResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisabledKnowledgeBaseArticleResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'disable knowledge base article response' => [
            'data' => [
                'kb_article' => [
                    'article_id' => 100,
                    'section_id' => 20,
                    'article_title' => 'Test article title 2',
                    'article_content' => 'Test article content 2',
                    'access_type' => 'public',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new DisabledKnowledgeBaseArticleResponse(
                kbArticle: new KnowledgeBaseArticleData(
                    articleId: 100,
                    sectionId: 20,
                    articleTitle: 'Test article title 2',
                    articleContent: 'Test article content 2',
                    accessType: 'public',
                    active: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DisabledKnowledgeBaseArticleResponse $expected): void
    {
        $actual = DisabledKnowledgeBaseArticleResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
