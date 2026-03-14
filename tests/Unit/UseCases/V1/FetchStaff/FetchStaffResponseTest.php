<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchStaff;

use Palach\Omnidesk\DTO\StaffData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStaff\Response as FetchStaffResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchStaffResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full staff data' => [
            'data' => [
                'staff' => [
                    'staff_id' => 200,
                    'staff_email' => 'staff@domain.ru',
                    'staff_signature' => 'Staff signature for email cases',
                    'staff_signature_chat' => 'Staff signature for chats',
                    'thumbnail' => '',
                    'active' => false,
                    'status' => 'online',
                    'status_id' => 1,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new FetchStaffResponse(
                staff: new StaffData(
                    staffId: 200,
                    staffEmail: 'staff@domain.ru',
                    staffSignature: 'Staff signature for email cases',
                    staffSignatureChat: 'Staff signature for chats',
                    thumbnail: '',
                    active: false,
                    status: 'online',
                    statusId: 1,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];

        yield 'minimal staff data' => [
            'data' => [
                'staff' => [
                    'staff_id' => 201,
                    'staff_email' => 'minimal@domain.ru',
                ],
            ],

            'expected' => new FetchStaffResponse(
                staff: new StaffData(
                    staffId: 201,
                    staffEmail: 'minimal@domain.ru',
                ),
            ),
        ];

        yield 'staff data with full name' => [
            'data' => [
                'staff' => [
                    'staff_id' => 202,
                    'staff_email' => 'john@domain.ru',
                    'staff_full_name' => 'John Doe',
                    'active' => true,
                    'status_id' => 2,
                ],
            ],

            'expected' => new FetchStaffResponse(
                staff: new StaffData(
                    staffId: 202,
                    staffEmail: 'john@domain.ru',
                    staffFullName: 'John Doe',
                    active: true,
                    statusId: 2,
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchStaffResponse $expected): void
    {
        $actual = FetchStaffResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
