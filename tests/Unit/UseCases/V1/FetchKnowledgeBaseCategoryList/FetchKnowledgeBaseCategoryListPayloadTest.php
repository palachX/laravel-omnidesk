<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchKnowledgeBaseCategoryList;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Payload as FetchKnowledgeBaseCategoryListPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\LaravelData\Optional;

final class FetchKnowledgeBaseCategoryListPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'page' => 2,
                'limit' => 50,
                'language_id' => '1',
            ],

            'expected' => new FetchKnowledgeBaseCategoryListPayload(
                page: 2,
                limit: 50,
                languageId: '1',
            ),
        ];

        yield 'not full data' => [
            'data' => [
                'language_id' => 'all',
            ],

            'expected' => new FetchKnowledgeBaseCategoryListPayload(
                languageId: 'all',
            ),
        ];

        yield 'empty data' => [
            'data' => [],

            'expected' => new FetchKnowledgeBaseCategoryListPayload,
        ];
    }

    public static function querySerializationProvider(): iterable
    {
        yield 'empty payload' => [
            [],
            [],
        ];

        yield 'page and limit' => [
            [
                'page' => 2,
                'limit' => 50,
            ],
            [
                'page' => 2,
                'limit' => 50,
            ],
        ];

        yield 'language_id as string' => [
            [
                'language_id' => '1',
            ],
            [
                'language_id' => '1',
            ],
        ];

        yield 'language_id as all' => [
            [
                'language_id' => 'all',
            ],
            [
                'language_id' => 'all',
            ],
        ];

        yield 'all parameters' => [
            [
                'page' => 3,
                'limit' => 25,
                'language_id' => '2',
            ],
            [
                'page' => 3,
                'limit' => 25,
                'language_id' => '2',
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchKnowledgeBaseCategoryListPayload $expected): void
    {
        $actual = FetchKnowledgeBaseCategoryListPayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('querySerializationProvider')]
    public function testToQuery(array $payloadArgs, array $expected): void
    {
        $payload = new FetchKnowledgeBaseCategoryListPayload(
            page: $payloadArgs['page'] ?? new Optional,
            limit: $payloadArgs['limit'] ?? new Optional,
            languageId: $payloadArgs['language_id'] ?? new Optional,
        );

        self::assertSame($expected, $payload->toQuery());
    }
}
