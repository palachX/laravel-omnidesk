<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchStaffList;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStaffList\Payload as FetchStaffListPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchStaffListPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'page' => 1,
                'limit' => 10,
                'language_id' => 'en',
            ],
            'expected' => new FetchStaffListPayload(
                page: 1,
                limit: 10,
                languageId: 'en',
            ),
        ];
        yield 'partial data' => [
            'data' => [
                'language_id' => 'ru',
            ],
            'expected' => new FetchStaffListPayload(
                languageId: 'ru',
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchStaffListPayload $expected): void
    {
        $actual = FetchStaffListPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }

    public static function toQueryProvider(): iterable
    {
        yield 'full data' => [
            'payload' => new FetchStaffListPayload(
                page: 1,
                limit: 10,
                languageId: 'en',
            ),
            'expected' => [
                'page' => 1,
                'limit' => 10,
                'language_id' => 'en',
            ],
        ];
        yield 'partial data' => [
            'payload' => new FetchStaffListPayload(
                languageId: 'ru',
            ),
            'expected' => [
                'language_id' => 'ru',
            ],
        ];
    }

    #[DataProvider('toQueryProvider')]
    public function testToQuery(FetchStaffListPayload $payload, array $expected): void
    {
        $actual = $payload->toQuery();

        $this->assertEquals($expected, $actual);
    }
}
