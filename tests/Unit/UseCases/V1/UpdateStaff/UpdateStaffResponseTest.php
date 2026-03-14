<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateStaff;

use Palach\Omnidesk\DTO\StaffData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateStaff\Response as UpdateStaffResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateStaffResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'staff' => [
                    'staff_id' => 200,
                    'staff_email' => 'staff@domain.ru',
                    'staff_full_name' => 'Staff full name changed',
                    'staff_signature' => 'Staff signature for email cases',
                    'staff_signature_chat' => 'Staff signature for chats',
                    'thumbnail' => '',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new UpdateStaffResponse(
                staff: new StaffData(
                    staffId: 200,
                    staffEmail: 'staff@domain.ru',
                    staffFullName: 'Staff full name changed',
                    staffSignature: 'Staff signature for email cases',
                    staffSignatureChat: 'Staff signature for chats',
                    thumbnail: '',
                    active: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                )
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'staff' => [
                    'staff_id' => 201,
                    'staff_email' => 'updated.staff@example.com',
                    'staff_full_name' => 'John Doe Updated',
                    'staff_signature' => '',
                    'staff_signature_chat' => '',
                    'thumbnail' => '',
                    'active' => true,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Thu, 25 Dec 2014 15:30:00 +0200',
                ],
            ],

            'expected' => new UpdateStaffResponse(
                staff: new StaffData(
                    staffId: 201,
                    staffEmail: 'updated.staff@example.com',
                    staffFullName: 'John Doe Updated',
                    staffSignature: '',
                    staffSignatureChat: '',
                    thumbnail: '',
                    active: true,
                    createdAt: 'Wed, 15 Jun 2023 14:30:00 +0300',
                    updatedAt: 'Thu, 25 Dec 2014 15:30:00 +0200',
                )
            ),
        ];

        yield 'with signatures only' => [
            'data' => [
                'staff' => [
                    'staff_id' => 202,
                    'staff_email' => 'staff@example.com',
                    'staff_full_name' => 'Jane Smith',
                    'staff_signature' => 'Updated email signature',
                    'staff_signature_chat' => 'Updated chat signature',
                    'thumbnail' => '',
                    'active' => true,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],

            'expected' => new UpdateStaffResponse(
                staff: new StaffData(
                    staffId: 202,
                    staffEmail: 'staff@example.com',
                    staffFullName: 'Jane Smith',
                    staffSignature: 'Updated email signature',
                    staffSignatureChat: 'Updated chat signature',
                    thumbnail: '',
                    active: true,
                    createdAt: 'Thu, 20 Jul 2023 09:15:00 +0300',
                    updatedAt: 'Fri, 26 Dec 2014 11:20:00 +0200',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testValidateAndCreate(array $data, UpdateStaffResponse $expected): void
    {
        $actual = UpdateStaffResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
