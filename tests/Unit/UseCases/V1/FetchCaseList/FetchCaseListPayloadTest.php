<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCaseList;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCaseList\Payload as FetchCaseListPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\LaravelData\Optional;

final class FetchCaseListPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'page' => 499,
                'limit' => 10,
                'status' => ['open'],
                'channel' => ['chh200'],
                'user_custom_id' => ['8e334869-a6ca-41da-b5cd-a8a51f99a529'],
            ],

            'expected' => new FetchCaseListPayload(
                status: ['open'],
                channel: ['chh200'],
                userCustomId: ['8e334869-a6ca-41da-b5cd-a8a51f99a529'],
                page: 499,
                limit: 10,
            ),
        ];

        yield 'not full data' => [
            'data' => [
                'status' => ['open'],
                'channel' => ['chh200'],
                'user_custom_id' => ['8e334869-a6ca-41da-b5cd-a8a51f99a529'],
            ],

            'expected' => new FetchCaseListPayload(
                status: ['open'],
                channel: ['chh200'],
                userCustomId: ['8e334869-a6ca-41da-b5cd-a8a51f99a529'],
            ),
        ];
    }

    public static function querySerializationProvider(): iterable
    {
        yield 'empty payload' => [
            [],
            [],
        ];

        yield 'single channel as scalar' => [
            [
                'channel' => ['ch21'],
            ],
            [
                'channel' => 'ch21',
            ],
        ];

        yield 'multiple channels as array' => [
            [
                'channel' => ['ch21', 'ch22'],
            ],
            [
                'channel' => ['ch21', 'ch22'],
            ],
        ];

        yield 'single status as scalar' => [
            [
                'status' => ['open'],
            ],
            [
                'status' => 'open',
            ],
        ];

        yield 'multiple statuses as array' => [
            [
                'status' => ['open', 'new'],
            ],
            [
                'status' => ['open', 'new'],
            ],
        ];

        yield 'mixed filters with pagination' => [
            [
                'channel' => ['ch21'],
                'status' => ['open', 'new'],
                'page' => 2,
                'limit' => 50,
            ],
            [
                'channel' => 'ch21',
                'status' => ['open', 'new'],
                'page' => 2,
                'limit' => 50,
            ],
        ];

        yield 'boolean flag serialized' => [
            [
                'showActiveChats' => true,
            ],
            [
                'show_active_chats' => true,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCaseListPayload $expected): void
    {
        $actual = FetchCaseListPayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }

    public function testSingleChannelSerializedAsScalar(): void
    {
        $payload = new FetchCaseListPayload(
            channel: ['ch21'],
        );

        $this->assertSame(
            ['channel' => 'ch21'],
            $payload->toQuery()
        );
    }

    #[DataProvider('querySerializationProvider')]
    public function testToQuery(array $payloadArgs, array $expected): void
    {
        $payload = new FetchCaseListPayload(
            status: $payloadArgs['status'] ?? new Optional,
            channel: $payloadArgs['channel'] ?? new Optional,
            userCustomId: $payloadArgs['userCustomId'] ?? new Optional,
            page: $payloadArgs['page'] ?? new Optional,
            limit: $payloadArgs['limit'] ?? new Optional,
            showActiveChats: $payloadArgs['showActiveChats'] ?? new Optional,
        );

        self::assertSame($expected, $payload->toQuery());
    }
}
