<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchStaffList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\StaffData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStaffList\Response as FetchStaffListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchStaffListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'staffs' => [
                    [
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
                    [
                        'staff_id' => 210,
                        'staff_email' => 'staff2@domain.ru',
                        'staff_signature' => 'Staff 2 signature for email cases',
                        'staff_signature_chat' => 'Staff 2 signature for chats',
                        'thumbnail' => 'http://domain.omnidesk.ru/path/avatar.jpeg',
                        'active' => true,
                        'status' => 'offline',
                        'status_id' => 2,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total' => 10,
            ],

            'expected' => new FetchStaffListResponse(
                staffs: new Collection([
                    new StaffData(
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
                    new StaffData(
                        staffId: 210,
                        staffEmail: 'staff2@domain.ru',
                        staffSignature: 'Staff 2 signature for email cases',
                        staffSignatureChat: 'Staff 2 signature for chats',
                        thumbnail: 'http://domain.omnidesk.ru/path/avatar.jpeg',
                        active: true,
                        status: 'offline',
                        statusId: 2,
                        createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                        updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ),
                ]),
                total: 10
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchStaffListResponse $expected): void
    {
        $actual = FetchStaffListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
