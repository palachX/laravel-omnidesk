<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateStaff;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateStaff\Payload as UpdateStaffPayload;
use Palach\Omnidesk\UseCases\V1\UpdateStaff\StaffUpdateData;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateStaffPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'staff' => [
                    'staff_email' => 'updated.staff@example.com',
                    'staff_full_name' => 'John Doe Updated',
                    'staff_signature' => 'Best regards, John',
                    'staff_signature_chat' => 'John - Support Team',
                ],
            ],

            'expected' => new UpdateStaffPayload(
                staff: new StaffUpdateData(
                    staffEmail: 'updated.staff@example.com',
                    staffFullName: 'John Doe Updated',
                    staffSignature: 'Best regards, John',
                    staffSignatureChat: 'John - Support Team'
                )
            ),
        ];

        yield 'partial data' => [
            'data' => [
                'staff' => [
                    'staff_full_name' => 'Jane Smith Updated',
                    'staff_signature' => 'Updated signature only',
                ],
            ],

            'expected' => new UpdateStaffPayload(
                staff: new StaffUpdateData(
                    staffFullName: 'Jane Smith Updated',
                    staffSignature: 'Updated signature only',
                )
            ),
        ];

        yield 'email only' => [
            'data' => [
                'staff' => [
                    'staff_email' => 'new.email@example.com',
                ],
            ],

            'expected' => new UpdateStaffPayload(
                staff: new StaffUpdateData(
                    staffEmail: 'new.email@example.com',
                )
            ),
        ];

        yield 'signatures only' => [
            'data' => [
                'staff' => [
                    'staff_signature' => 'Email signature',
                    'staff_signature_chat' => 'Chat signature',
                ],
            ],

            'expected' => new UpdateStaffPayload(
                staff: new StaffUpdateData(
                    staffSignature: 'Email signature',
                    staffSignatureChat: 'Chat signature',
                )
            ),
        ];

        yield 'minimal data with one field' => [
            'data' => [
                'staff' => [
                    'staff_full_name' => 'Minimal Update',
                ],
            ],

            'expected' => new UpdateStaffPayload(
                staff: new StaffUpdateData(
                    staffFullName: 'Minimal Update',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testValidateAndCreate(array $data, UpdateStaffPayload $expected): void
    {
        $actual = UpdateStaffPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
