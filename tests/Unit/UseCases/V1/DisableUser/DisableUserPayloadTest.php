<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisableUser;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableUser\Payload as DisableUserPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisableUserPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['user_id' => 1],
            'expected' => new DisableUserPayload(userId: 1),
        ];

        yield 'medium id' => [
            'data' => ['user_id' => 12345],
            'expected' => new DisableUserPayload(userId: 12345),
        ];

        yield 'large id' => [
            'data' => ['user_id' => 999999999],
            'expected' => new DisableUserPayload(userId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DisableUserPayload $expected): void
    {
        $actual = DisableUserPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
