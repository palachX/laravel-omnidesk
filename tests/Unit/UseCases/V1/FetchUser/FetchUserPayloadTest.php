<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchUser;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchUser\Payload as FetchUserPayload;
use Palach\Omnidesk\UseCases\V1\FetchUser\UserFetchData;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchUserPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'user id 200' => [
            'data' => [
                'user' => [
                    'user_id' => 200,
                ],
            ],

            'expected' => new FetchUserPayload(
                user: new UserFetchData(
                    userId: 200
                )
            ),
        ];

        yield 'user id 123' => [
            'data' => [
                'user' => [
                    'user_id' => 123,
                ],
            ],

            'expected' => new FetchUserPayload(
                user: new UserFetchData(
                    userId: 123
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchUserPayload $expected): void
    {
        $actual = FetchUserPayload::validateAndCreate($data);
        $this->assertSame($expected->toArray(), $actual->toArray());
    }
}
