<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreStaff;

use Palach\Omnidesk\DTO\StaffData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreStaff\Response as StoreStaffResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreStaffResponseTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'staff with all fields' => [
            'data' => [
                'staff' => [
                    'staff_id' => 123,
                    'staff_email' => 'staff@domain.ru',
                    'staff_full_name' => 'Staff full name',
                    'staff_signature' => 'Staff signature for email cases',
                    'staff_signature_chat' => 'Staff signature for chats',
                    'thumbnail' => 'https://example.com/avatar.jpg',
                    'active' => true,
                    'created_at' => '2023-01-01T00:00:00Z',
                    'updated_at' => '2023-01-01T00:00:00Z',
                ],
            ],
            'expected' => new StoreStaffResponse(
                staff: new StaffData(
                    staffId: 123,
                    staffEmail: 'staff@domain.ru',
                    staffFullName: 'Staff full name',
                    staffSignature: 'Staff signature for email cases',
                    staffSignatureChat: 'Staff signature for chats',
                    thumbnail: 'https://example.com/avatar.jpg',
                    active: true,
                    createdAt: '2023-01-01T00:00:00Z',
                    updatedAt: '2023-01-01T00:00:00Z'
                )
            ),
        ];

        yield 'staff with only required fields' => [
            'data' => [
                'staff' => [
                    'staff_id' => 456,
                    'staff_email' => 'minimal@domain.ru',
                ],
            ],
            'expected' => new StoreStaffResponse(
                staff: new StaffData(
                    staffId: 456,
                    staffEmail: 'minimal@domain.ru'
                )
            ),
        ];

        yield 'staff with some optional fields' => [
            'data' => [
                'staff' => [
                    'staff_id' => 789,
                    'staff_email' => 'partial@domain.ru',
                    'staff_full_name' => 'Partial Staff Name',
                    'staff_signature' => 'Some signature',
                    'active' => false,
                ],
            ],
            'expected' => new StoreStaffResponse(
                staff: new StaffData(
                    staffId: 789,
                    staffEmail: 'partial@domain.ru',
                    staffFullName: 'Partial Staff Name',
                    staffSignature: 'Some signature',
                    active: false
                )
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, StoreStaffResponse $expected): void
    {
        $response = StoreStaffResponse::from($data);

        $this->assertEquals($expected, $response);
    }
}
