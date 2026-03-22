<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\BlockUser;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\BlockUser\Payload as BlockUserPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class BlockUserPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['user_id' => 1],
            'expected' => new BlockUserPayload(userId: 1),
        ];

        yield 'medium id' => [
            'data' => ['user_id' => 12345],
            'expected' => new BlockUserPayload(userId: 12345),
        ];

        yield 'large id' => [
            'data' => ['user_id' => 999999999],
            'expected' => new BlockUserPayload(userId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, BlockUserPayload $expected): void
    {
        $actual = BlockUserPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
