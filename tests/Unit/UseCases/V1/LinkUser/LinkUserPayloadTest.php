<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\LinkUser;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\LinkUser\Payload as LinkUserPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class LinkUserPayloadTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'user_email only' => [
            'data' => [
                'user_email' => 'user@domain.ru',
            ],
            'expected' => new LinkUserPayload(
                userEmail: 'user@domain.ru',
            ),
        ];

        yield 'user_phone only' => [
            'data' => [
                'user_phone' => '+1234567890',
            ],
            'expected' => new LinkUserPayload(
                userPhone: '+1234567890',
            ),
        ];

        yield 'user_id only' => [
            'data' => [
                'user_id' => 123456,
            ],
            'expected' => new LinkUserPayload(
                userId: 123456,
            ),
        ];

        yield 'empty data' => [
            'data' => [],
            'expected' => new LinkUserPayload,
        ];

        yield 'all fields' => [
            'data' => [
                'user_email' => 'user@domain.ru',
                'user_phone' => '+1234567890',
                'user_id' => 123456,
            ],
            'expected' => new LinkUserPayload(
                userEmail: 'user@domain.ru',
                userPhone: '+1234567890',
                userId: 123456,
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, LinkUserPayload $expected): void
    {
        $actual = LinkUserPayload::validateAndCreate($data);
        $this->assertEquals($expected->toArray(), $actual->toArray());
    }
}
