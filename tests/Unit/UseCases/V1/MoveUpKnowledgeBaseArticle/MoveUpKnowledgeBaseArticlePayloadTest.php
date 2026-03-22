<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\MoveUpKnowledgeBaseArticle;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseArticle\Payload as MoveUpKnowledgeBaseArticlePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class MoveUpKnowledgeBaseArticlePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['article_id' => 1],
            'expected' => new MoveUpKnowledgeBaseArticlePayload(articleId: 1),
        ];

        yield 'medium id' => [
            'data' => ['article_id' => 12345],
            'expected' => new MoveUpKnowledgeBaseArticlePayload(articleId: 12345),
        ];

        yield 'large id' => [
            'data' => ['article_id' => 999999999],
            'expected' => new MoveUpKnowledgeBaseArticlePayload(articleId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, MoveUpKnowledgeBaseArticlePayload $expected): void
    {
        $actual = MoveUpKnowledgeBaseArticlePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
