<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\EnableKnowledgeBaseArticle;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\EnableArticle\Payload as EnableArticlePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class EnableKnowledgeBaseArticlePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['article_id' => 1],
            'expected' => new EnableArticlePayload(articleId: 1),
        ];

        yield 'medium id' => [
            'data' => ['article_id' => 12345],
            'expected' => new EnableArticlePayload(articleId: 12345),
        ];

        yield 'large id' => [
            'data' => ['article_id' => 999999999],
            'expected' => new EnableArticlePayload(articleId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, EnableArticlePayload $expected): void
    {
        $actual = EnableArticlePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
