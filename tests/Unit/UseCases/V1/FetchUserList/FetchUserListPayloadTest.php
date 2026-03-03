<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchUserList;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchUserList\Payload as FetchUserListPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\LaravelData\Optional;

final class FetchUserListPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'page' => 1,
                'limit' => 10,
                'user_email' => 'test@example.com',
                'user_phone' => '+1234567890',
                'user_custom_id' => '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                'user_custom_channel' => 'cch101',
                'company_id' => [123, 456],
                'language_id' => 2,
                'custom_fields' => ['cf_20' => 'some data', 'cf_23' => true],
                'amount_of_cases' => true,
                'from_time' => '2023-01-01',
                'to_time' => '2023-12-31',
                'from_updated_time' => '2023-01-01',
                'to_updated_time' => '2023-12-31',
                'from_last_contact_time' => '2023-01-01',
                'to_last_contact_time' => '2023-12-31',
            ],

            'expected' => new FetchUserListPayload(
                page: 1,
                limit: 10,
                userEmail: 'test@example.com',
                userPhone: '+1234567890',
                userCustomId: '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                userCustomChannel: 'cch101',
                companyId: [123, 456],
                languageId: 2,
                customFields: ['cf_20' => 'some data', 'cf_23' => true],
                amountOfCases: true,
                fromTime: '2023-01-01',
                toTime: '2023-12-31',
                fromUpdatedTime: '2023-01-01',
                toUpdatedTime: '2023-12-31',
                fromLastContactTime: '2023-01-01',
                toLastContactTime: '2023-12-31',
            ),
        ];

        yield 'not full data' => [
            'data' => [
                'user_email' => 'test@example.com',
                'company_id' => [123],
            ],

            'expected' => new FetchUserListPayload(
                userEmail: 'test@example.com',
                companyId: [123],
            ),
        ];
    }

    public static function querySerializationProvider(): iterable
    {
        yield 'empty payload' => [
            [],
            [],
        ];

        yield 'single company_id as scalar' => [
            [
                'company_id' => [123],
            ],
            [
                'company_id' => '123',
            ],
        ];

        yield 'multiple company_id as array' => [
            [
                'company_id' => [123, 456],
            ],
            [
                'company_id' => ['123', '456'],
            ],
        ];

        yield 'single custom_fields as scalar' => [
            [
                'custom_fields' => ['cf_20' => 'value'],
            ],
            [
                'custom_fields' => ['cf_20' => 'value'],
            ],
        ];

        yield 'multiple custom_fields as array' => [
            [
                'custom_fields' => ['cf_20' => 'value', 'cf_21' => 'value2'],
            ],
            [
                'custom_fields' => ['cf_20' => 'value', 'cf_21' => 'value2'],
            ],
        ];

        yield 'mixed filters with pagination' => [
            [
                'company_id' => [123],
                'user_email' => 'test@example.com',
                'page' => 2,
                'limit' => 50,
            ],
            [
                'company_id' => '123',
                'user_email' => 'test@example.com',
                'page' => 2,
                'limit' => 50,
            ],
        ];

        yield 'boolean flag serialized' => [
            [
                'amount_of_cases' => true,
            ],
            [
                'amount_of_cases' => true,
            ],
        ];

        yield 'time filters serialized' => [
            [
                'from_time' => '2023-01-01',
                'to_time' => '2023-12-31',
                'from_updated_time' => '2023-01-01',
                'to_updated_time' => '2023-12-31',
                'from_last_contact_time' => '2023-01-01',
                'to_last_contact_time' => '2023-12-31',
            ],
            [
                'from_time' => '2023-01-01',
                'to_time' => '2023-12-31',
                'from_updated_time' => '2023-01-01',
                'to_updated_time' => '2023-12-31',
                'from_last_contact_time' => '2023-01-01',
                'to_last_contact_time' => '2023-12-31',
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchUserListPayload $expected): void
    {
        $actual = FetchUserListPayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }

    public function testSingleCompanyIdSerializedAsScalar(): void
    {
        $payload = new FetchUserListPayload(
            companyId: [123],
        );

        $this->assertSame(
            ['company_id' => '123'],
            $payload->toQuery()
        );
    }

    #[DataProvider('querySerializationProvider')]
    public function testToQuery(array $payloadArgs, array $expected): void
    {
        $payload = new FetchUserListPayload(
            page: $payloadArgs['page'] ?? new Optional,
            limit: $payloadArgs['limit'] ?? new Optional,
            userEmail: $payloadArgs['user_email'] ?? new Optional,
            userPhone: $payloadArgs['user_phone'] ?? new Optional,
            userCustomId: $payloadArgs['user_custom_id'] ?? new Optional,
            userCustomChannel: $payloadArgs['user_custom_channel'] ?? new Optional,
            companyId: $payloadArgs['company_id'] ?? new Optional,
            languageId: $payloadArgs['language_id'] ?? new Optional,
            customFields: $payloadArgs['custom_fields'] ?? new Optional,
            amountOfCases: $payloadArgs['amount_of_cases'] ?? new Optional,
            fromTime: $payloadArgs['from_time'] ?? new Optional,
            toTime: $payloadArgs['to_time'] ?? new Optional,
            fromUpdatedTime: $payloadArgs['from_updated_time'] ?? new Optional,
            toUpdatedTime: $payloadArgs['to_updated_time'] ?? new Optional,
            fromLastContactTime: $payloadArgs['from_last_contact_time'] ?? new Optional,
            toLastContactTime: $payloadArgs['to_last_contact_time'] ?? new Optional,
        );

        $this->assertEquals($expected, $payload->toQuery());
    }
}
