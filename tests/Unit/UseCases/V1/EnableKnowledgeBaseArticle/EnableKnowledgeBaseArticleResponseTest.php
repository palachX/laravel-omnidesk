<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\EnableKnowledgeBaseArticle;

use Palach\Omnidesk\DTO\KnowledgeBaseArticleData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\EnableKnowledgeBaseArticle\Response as EnabledKnowledgeBaseArticleResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class EnableKnowledgeBaseArticleResponseTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'enable knowledge base article response' => [
            'data' => [
                'kb_article' => [
                    'article_id' => 100,
                    'section_id' => 20,
                    'article_title' => 'Test article title 2',
                    'article_content' => 'Test article content 2',
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => [
                'kb_article' => [
                    'article_id' => 100,
                    'section_id' => 20,
                    'article_title' => 'Test article title 2',
                    'article_content' => 'Test article content 2',
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testResponse(array $data, array $expected): void
    {
        $response = EnabledKnowledgeBaseArticleResponse::from($data);

        $this->assertInstanceOf(KnowledgeBaseArticleData::class, $response->kbArticle);
        $this->assertEquals($expected, $response->toArray());
    }
}
