<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\MoveDownKnowledgeBaseArticle;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseArticle\Payload as MoveDownKnowledgeBaseArticlePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class MoveDownKnowledgeBaseArticlePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['article_id' => 1],
            'expected' => new MoveDownKnowledgeBaseArticlePayload(articleId: 1),
        ];

        yield 'medium id' => [
            'data' => ['article_id' => 12345],
            'expected' => new MoveDownKnowledgeBaseArticlePayload(articleId: 12345),
        ];

        yield 'large id' => [
            'data' => ['article_id' => 999999999],
            'expected' => new MoveDownKnowledgeBaseArticlePayload(articleId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, MoveDownKnowledgeBaseArticlePayload $expected): void
    {
        $actual = MoveDownKnowledgeBaseArticlePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
