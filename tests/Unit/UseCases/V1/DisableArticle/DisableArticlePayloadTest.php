<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisableArticle;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableArticle\Payload as DisableArticlePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisableArticlePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['article_id' => 1],
            'expected' => new DisableArticlePayload(articleId: 1),
        ];

        yield 'medium id' => [
            'data' => ['article_id' => 12345],
            'expected' => new DisableArticlePayload(articleId: 12345),
        ];

        yield 'large id' => [
            'data' => ['article_id' => 999999999],
            'expected' => new DisableArticlePayload(articleId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DisableArticlePayload $expected): void
    {
        $actual = DisableArticlePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
