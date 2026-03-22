<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisableStaff;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableStaff\DisabledStaffData;
use Palach\Omnidesk\UseCases\V1\DisableStaff\Payload as DisabledStaffPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisableStaffPayloadTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'disable staff with replace staff id' => [
            'data' => [
                'staff' => [
                    'replace_staff_id' => 300,
                ],
            ],
            'expected' => new DisabledStaffPayload(
                staff: new DisabledStaffData(
                    replaceStaffId: 300
                )
            ),
        ];

        yield 'disable staff with different replace staff id' => [
            'data' => [
                'staff' => [
                    'replace_staff_id' => 450,
                ],
            ],
            'expected' => new DisabledStaffPayload(
                staff: new DisabledStaffData(
                    replaceStaffId: 450
                )
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, DisabledStaffPayload $expected): void
    {
        $payload = DisabledStaffPayload::from($data);

        $this->assertEquals($expected, $payload);
    }
}
