<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchUserIdentification;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Response as FetchUserIdentificationResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchUserIdentificationResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'standard code' => [
            'data' => [
                'code' => 'o_37BD49_uv',
            ],

            'expected' => new FetchUserIdentificationResponse(
                code: 'o_37BD49_uv',
            ),
        ];

        yield 'short code' => [
            'data' => [
                'code' => 'o_ABC123_xy',
            ],

            'expected' => new FetchUserIdentificationResponse(
                code: 'o_ABC123_xy',
            ),
        ];

        yield 'numeric code' => [
            'data' => [
                'code' => 'o_123456_ab',
            ],

            'expected' => new FetchUserIdentificationResponse(
                code: 'o_123456_ab',
            ),
        ];

        yield 'alphanumeric code' => [
            'data' => [
                'code' => 'o_DEF456_zz',
            ],

            'expected' => new FetchUserIdentificationResponse(
                code: 'o_DEF456_zz',
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchUserIdentificationResponse $expected): void
    {
        $actual = FetchUserIdentificationResponse::from($data);

        $this->assertEquals($expected, $actual);
    }
}
