<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreStaff;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreStaff\Payload as StoreStaffPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreStaffPayloadTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'staff with all fields' => [
            'data' => [
                'staff_email' => 'staff@domain.ru',
                'staff_full_name' => 'Staff full name',
                'staff_signature' => 'Staff signature for email cases',
                'staff_signature_chat' => 'Staff signature for chats',
            ],
            'expected' => new StoreStaffPayload(
                staffEmail: 'staff@domain.ru',
                staffFullName: 'Staff full name',
                staffSignature: 'Staff signature for email cases',
                staffSignatureChat: 'Staff signature for chats'
            ),
        ];

        yield 'staff with only required field' => [
            'data' => [
                'staff_email' => 'minimal@domain.ru',
            ],
            'expected' => new StoreStaffPayload(
                staffEmail: 'minimal@domain.ru'
            ),
        ];

        yield 'staff with some optional fields' => [
            'data' => [
                'staff_email' => 'partial@domain.ru',
                'staff_full_name' => 'Partial Staff Name',
                'staff_signature' => 'Some signature',
            ],
            'expected' => new StoreStaffPayload(
                staffEmail: 'partial@domain.ru',
                staffFullName: 'Partial Staff Name',
                staffSignature: 'Some signature'
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, StoreStaffPayload $expected): void
    {
        $payload = StoreStaffPayload::from($data);

        $this->assertEquals($expected, $payload);
    }
}
