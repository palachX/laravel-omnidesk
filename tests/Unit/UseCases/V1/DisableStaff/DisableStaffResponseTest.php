<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisableStaff;

use Palach\Omnidesk\DTO\StaffData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableStaff\Response as DisabledStaffResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisableStaffResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'disable staff response' => [
            'data' => [
                'staff' => [
                    'staff_id' => 200,
                    'staff_email' => 'staff@domain.ru',
                    'staff_full_name' => 'Staff full name changed',
                    'staff_signature' => 'Staff signature for email cases',
                    'staff_signature_chat' => 'Staff signature for chats',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new DisabledStaffResponse(
                staff: new StaffData(
                    staffId: 200,
                    staffEmail: 'staff@domain.ru',
                    staffFullName: 'Staff full name changed',
                    staffSignature: 'Staff signature for email cases',
                    staffSignatureChat: 'Staff signature for chats',
                    active: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DisabledStaffResponse $expected): void
    {
        $actual = DisabledStaffResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
