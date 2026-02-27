<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCaseMessages;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCaseMessages\Payload as FetchCaseMessagesPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\LaravelData\Optional;

final class FetchCaseMessagesPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'case_id' => 2000,
                'page' => 2,
                'limit' => 50,
                'order' => 'desc',
            ],

            'expected' => new FetchCaseMessagesPayload(
                caseId: 2000,
                page: 2,
                limit: 50,
                order: 'desc',
            ),
        ];

        yield 'required only' => [
            'data' => [
                'case_id' => 2000,
            ],

            'expected' => new FetchCaseMessagesPayload(
                caseId: 2000,
            ),
        ];
    }

    public static function querySerializationProvider(): iterable
    {
        yield 'empty optional parameters' => [
            [
                'case_id' => 2000,
            ],
            [],
        ];

        yield 'with page only' => [
            [
                'case_id' => 2000,
                'page' => 2,
            ],
            [
                'page' => 2,
            ],
        ];

        yield 'with limit only' => [
            [
                'case_id' => 2000,
                'limit' => 50,
            ],
            [
                'limit' => 50,
            ],
        ];

        yield 'with order only' => [
            [
                'case_id' => 2000,
                'order' => 'desc',
            ],
            [
                'order' => 'desc',
            ],
        ];

        yield 'with all optional parameters' => [
            [
                'case_id' => 2000,
                'page' => 2,
                'limit' => 50,
                'order' => 'asc',
            ],
            [
                'page' => 2,
                'limit' => 50,
                'order' => 'asc',
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCaseMessagesPayload $expected): void
    {
        $actual = FetchCaseMessagesPayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('querySerializationProvider')]
    public function testToQuery(array $payloadArgs, array $expected): void
    {
        $payload = new FetchCaseMessagesPayload(
            caseId: $payloadArgs['case_id'],
            page: $payloadArgs['page'] ?? new Optional,
            limit: $payloadArgs['limit'] ?? new Optional,
            order: $payloadArgs['order'] ?? new Optional,
        );

        self::assertSame($expected, $payload->toQuery());
    }
}
