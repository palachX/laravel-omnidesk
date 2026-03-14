<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteStaff;

use Palach\Omnidesk\DTO\StaffData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteStaff\Response as DeleteStaffResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteStaffResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'delete staff response' => [
            'data' => [
                'staff' => [
                    'staff_id' => 100,
                    'staff_full_name' => 'John Doe',
                    'staff_email' => 'john@example.com',
                    'staff_signature' => '',
                    'staff_signature_chat' => '',
                    'thumbnail' => '',
                    'active' => false,
                    'status' => 'deleted',
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new DeleteStaffResponse(
                staff: new StaffData(
                    staffId: 100,
                    staffEmail: 'john@example.com',
                    staffFullName: 'John Doe',
                    staffSignature: '',
                    staffSignatureChat: '',
                    thumbnail: '',
                    active: false,
                    status: 'deleted',
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteStaffResponse $expected): void
    {
        $actual = DeleteStaffResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
