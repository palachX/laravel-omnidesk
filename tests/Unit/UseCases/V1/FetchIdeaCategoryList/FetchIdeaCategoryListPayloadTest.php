<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchIdeaCategoryList;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategoryList\Payload as FetchIdeaCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\LaravelData\Optional;

final class FetchIdeaCategoryListPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'page' => 2,
                'limit' => 50,
            ],

            'expected' => new FetchIdeaCategoryPayload(
                page: 2,
                limit: 50,
            ),
        ];

        yield 'not full data' => [
            'data' => [],

            'expected' => new FetchIdeaCategoryPayload,
        ];
    }

    public static function querySerializationProvider(): iterable
    {
        yield 'empty payload' => [
            [],
            [],
        ];

        yield 'with page only' => [
            [
                'page' => 2,
            ],
            [
                'page' => 2,
            ],
        ];

        yield 'with limit only' => [
            [
                'limit' => 25,
            ],
            [
                'limit' => 25,
            ],
        ];

        yield 'with both page and limit' => [
            [
                'page' => 3,
                'limit' => 75,
            ],
            [
                'page' => 3,
                'limit' => 75,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchIdeaCategoryPayload $expected): void
    {
        $actual = FetchIdeaCategoryPayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('querySerializationProvider')]
    public function testToQuery(array $payloadArgs, array $expected): void
    {
        $payload = new FetchIdeaCategoryPayload(
            page: $payloadArgs['page'] ?? new Optional,
            limit: $payloadArgs['limit'] ?? new Optional,
        );

        self::assertSame($expected, $payload->toQuery());
    }
}
