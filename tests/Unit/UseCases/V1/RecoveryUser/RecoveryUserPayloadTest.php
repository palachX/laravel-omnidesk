<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\RecoveryUser;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\RecoveryUser\Payload as RecoveryUserPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class RecoveryUserPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['user_id' => 1],
            'expected' => new RecoveryUserPayload(userId: 1),
        ];

        yield 'medium id' => [
            'data' => ['user_id' => 12345],
            'expected' => new RecoveryUserPayload(userId: 12345),
        ];

        yield 'large id' => [
            'data' => ['user_id' => 999999999],
            'expected' => new RecoveryUserPayload(userId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, RecoveryUserPayload $expected): void
    {
        $actual = RecoveryUserPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
