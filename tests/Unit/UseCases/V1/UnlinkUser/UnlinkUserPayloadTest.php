<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UnlinkUser;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UnlinkUser\Payload as UnlinkUserPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class UnlinkUserPayloadTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'user_id only' => [
            'data' => [
                'user_id' => 25830712,
            ],
            'expected' => new UnlinkUserPayload(
                userId: 25830712,
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, UnlinkUserPayload $expected): void
    {
        $actual = UnlinkUserPayload::validateAndCreate($data);
        $this->assertEquals($expected->toArray(), $actual->toArray());
    }
}
