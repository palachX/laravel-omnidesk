<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchUserIdentification;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Payload as FetchUserIdentificationPayload;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\UserIdentificationData;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchUserIdentificationPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with email' => [
            'data' => [
                'user' => [
                    'user_full_name' => 'Семёнов Алексей',
                    'company_name' => 'ABCompany',
                    'user_email' => 'a.semenov@abcompany.com',
                    'user_phone' => '+79221110000',
                    'user_whatsapp_phone' => '+79221110000',
                    'user_custom_id' => 'a.semenov',
                    'user_custom_channel' => '481',
                    'company_position' => 'Developer',
                    'user_note' => 'VIP customer',
                    'language_id' => 1,
                    'custom_fields' => [
                        'cf_7264' => 'some data',
                        'cf_7786' => 2,
                        'cf_7486' => true,
                    ],
                ],
            ],

            'expected' => new FetchUserIdentificationPayload(
                user: new UserIdentificationData(
                    userEmail: 'a.semenov@abcompany.com',
                    userPhone: '+79221110000',
                    userWhatsappPhone: '+79221110000',
                    userCustomId: 'a.semenov',
                    userCustomChannel: '481',
                    userFullName: 'Семёнов Алексей',
                    companyName: 'ABCompany',
                    companyPosition: 'Developer',
                    userNote: 'VIP customer',
                    languageId: 1,
                    customFields: [
                        'cf_7264' => 'some data',
                        'cf_7786' => 2,
                        'cf_7486' => true,
                    ]
                )
            ),
        ];

        yield 'minimal data with email only' => [
            'data' => [
                'user' => [
                    'user_email' => 'john.doe@example.com',
                ],
            ],

            'expected' => new FetchUserIdentificationPayload(
                user: new UserIdentificationData(
                    userEmail: 'john.doe@example.com',
                )
            ),
        ];

        yield 'data with telegram' => [
            'data' => [
                'user' => [
                    'user_telegram_data' => 'john_doe_telegram',
                    'user_full_name' => 'John Smith',
                    'company_name' => 'Tech Company',
                ],
            ],

            'expected' => new FetchUserIdentificationPayload(
                user: new UserIdentificationData(
                    userTelegramData: 'john_doe_telegram',
                    userFullName: 'John Smith',
                    companyName: 'Tech Company',
                )
            ),
        ];

        yield 'data with whatsapp only' => [
            'data' => [
                'user' => [
                    'user_whatsapp_phone' => '+1234567890',
                    'user_full_name' => 'Jane Doe',
                ],
            ],

            'expected' => new FetchUserIdentificationPayload(
                user: new UserIdentificationData(
                    userWhatsappPhone: '+1234567890',
                    userFullName: 'Jane Doe',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchUserIdentificationPayload $expected): void
    {
        $actual = FetchUserIdentificationPayload::from($data);

        $this->assertEquals($expected, $actual);
    }
}
