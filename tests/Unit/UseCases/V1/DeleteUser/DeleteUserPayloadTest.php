<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteUser;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteUser\Payload as DeleteUserPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteUserPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['user_id' => 1],
            'expected' => new DeleteUserPayload(userId: 1),
        ];

        yield 'medium id' => [
            'data' => ['user_id' => 12345],
            'expected' => new DeleteUserPayload(userId: 12345),
        ];

        yield 'large id' => [
            'data' => ['user_id' => 999999999],
            'expected' => new DeleteUserPayload(userId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteUserPayload $expected): void
    {
        $actual = DeleteUserPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
