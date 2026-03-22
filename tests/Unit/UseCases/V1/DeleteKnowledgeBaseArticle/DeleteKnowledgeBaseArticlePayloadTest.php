<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteKnowledgeBaseArticle;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteKnowledgeBaseArticle\Payload as DeleteKnowledgeBaseArticlePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteKnowledgeBaseArticlePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['article_id' => 1],
            'expected' => new DeleteKnowledgeBaseArticlePayload(articleId: 1),
        ];

        yield 'medium id' => [
            'data' => ['article_id' => 12345],
            'expected' => new DeleteKnowledgeBaseArticlePayload(articleId: 12345),
        ];

        yield 'large id' => [
            'data' => ['article_id' => 999999999],
            'expected' => new DeleteKnowledgeBaseArticlePayload(articleId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteKnowledgeBaseArticlePayload $expected): void
    {
        $actual = DeleteKnowledgeBaseArticlePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
