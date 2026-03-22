<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\EnableStaff;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\EnableStaff\Payload as EnableStaffPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class EnableStaffPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['staff_id' => 1],
            'expected' => new EnableStaffPayload(staffId: 1),
        ];

        yield 'medium id' => [
            'data' => ['staff_id' => 12345],
            'expected' => new EnableStaffPayload(staffId: 12345),
        ];

        yield 'large id' => [
            'data' => ['staff_id' => 999999999],
            'expected' => new EnableStaffPayload(staffId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, EnableStaffPayload $expected): void
    {
        $actual = EnableStaffPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
