<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteStaff;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteStaff\DeleteStaffData;
use Palach\Omnidesk\UseCases\V1\DeleteStaff\Payload as DeleteStaffPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteStaffPayloadTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'disable staff with replace staff id' => [
            'data' => [
                'staff_id' => 123,
                'staff' => [
                    'replace_staff_id' => 300,
                ],
            ],
            'expected' => new DeleteStaffPayload(
                staffId: 123,
                staff: new DeleteStaffData(
                    replaceStaffId: 300
                )
            ),
        ];

        yield 'disable staff with different replace staff id' => [
            'data' => [
                'staff_id' => 124,
                'staff' => [
                    'replace_staff_id' => 450,
                ],
            ],
            'expected' => new DeleteStaffPayload(
                staffId: 124,
                staff: new DeleteStaffData(
                    replaceStaffId: 450
                )
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, DeleteStaffPayload $expected): void
    {
        $payload = DeleteStaffPayload::from($data);

        $this->assertEquals($expected, $payload);
    }
}
