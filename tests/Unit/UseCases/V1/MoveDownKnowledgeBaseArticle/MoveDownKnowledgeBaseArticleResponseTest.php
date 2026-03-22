<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\MoveDownKnowledgeBaseArticle;

use Palach\Omnidesk\DTO\KnowledgeBaseArticleData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseArticle\Response;
use PHPUnit\Framework\Attributes\DataProvider;

final class MoveDownKnowledgeBaseArticleResponseTest extends AbstractTestCase
{
    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data): void
    {
        $response = Response::from($data);

        $this->assertInstanceOf(KnowledgeBaseArticleData::class, $response->kbArticle);
        $this->assertEquals($data['kb_article']['article_id'], $response->kbArticle->articleId);
        $this->assertEquals($data['kb_article']['section_id'], $response->kbArticle->sectionId);
        $this->assertEquals($data['kb_article']['article_title'], $response->kbArticle->articleTitle);
        $this->assertEquals($data['kb_article']['article_content'], $response->kbArticle->articleContent);
        $this->assertEquals($data['kb_article']['access_type'], $response->kbArticle->accessType);
        $this->assertEquals($data['kb_article']['active'], $response->kbArticle->active);
        $this->assertEquals($data['kb_article']['created_at'], $response->kbArticle->createdAt);
        $this->assertEquals($data['kb_article']['updated_at'], $response->kbArticle->updatedAt);
    }

    public static function dataArrayProvider(): iterable
    {
        yield 'move down knowledge base article response' => [
            [
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
}
