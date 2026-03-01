<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreUser;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreUser\Payload as StoreUserPayload;
use Palach\Omnidesk\UseCases\V1\StoreUser\UserStoreData;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreUserPayloadTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'email user' => [
            'data' => [
                'user' => [
                    'user_email' => 'user@domain.ru',
                    'user_full_name' => "User's full name",
                    'company_name' => "User's company name",
                    'company_position' => "User's position",
                    'user_note' => 'Some note',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                ],
            ],

            'expected' => new StoreUserPayload(
                user: new UserStoreData(
                    userEmail: 'user@domain.ru',
                    userFullName: "User's full name",
                    companyName: "User's company name",
                    companyPosition: "User's position",
                    userNote: 'Some note',
                    languageId: 2,
                    customFields: [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ]
                )
            ),
        ];

        yield 'whatsapp user' => [
            'data' => [
                'user' => [
                    'user_whatsapp_phone' => '+79261234567',
                    'user_full_name' => 'John Doe',
                    'company_name' => 'Example Corp',
                    'company_position' => 'Developer',
                    'user_note' => 'VIP customer',
                    'language_id' => 1,
                ],
            ],

            'expected' => new StoreUserPayload(
                user: new UserStoreData(
                    userWhatsappPhone: '+79261234567',
                    userFullName: 'John Doe',
                    companyName: 'Example Corp',
                    companyPosition: 'Developer',
                    userNote: 'VIP customer',
                    languageId: 1
                )
            ),
        ];

        yield 'telegram user' => [
            'data' => [
                'user' => [
                    'user_telegram' => '123456789',
                    'user_full_name' => 'Jane Smith',
                    'company_name' => 'Tech Company',
                    'company_position' => 'Manager',
                    'user_note' => 'Important client',
                    'language_id' => 2,
                ],
            ],

            'expected' => new StoreUserPayload(
                user: new UserStoreData(
                    userTelegram: '123456789',
                    userFullName: 'Jane Smith',
                    companyName: 'Tech Company',
                    companyPosition: 'Manager',
                    userNote: 'Important client',
                    languageId: 2
                )
            ),
        ];

        yield 'custom channel user' => [
            'data' => [
                'user' => [
                    'user_custom_id' => 'customer_12345',
                    'user_custom_channel' => 'cch101',
                    'user_full_name' => 'Custom User',
                    'company_name' => 'Custom Corp',
                ],
            ],

            'expected' => new StoreUserPayload(
                user: new UserStoreData(
                    userCustomId: 'customer_12345',
                    userCustomChannel: 'cch101',
                    userFullName: 'Custom User',
                    companyName: 'Custom Corp'
                )
            ),
        ];

        yield 'minimal user' => [
            'data' => [
                'user' => [
                    'user_phone' => '+79998887755',
                ],
            ],

            'expected' => new StoreUserPayload(
                user: new UserStoreData(
                    userPhone: '+79998887755'
                )
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testPayload(array $data, StoreUserPayload $expected): void
    {
        $payload = StoreUserPayload::from($data);

        $this->assertEquals($expected, $payload);
    }

    #[DataProvider('dataArrayProvider')]
    public function testPayloadToArray(array $data, StoreUserPayload $expected): void
    {
        $payload = StoreUserPayload::from($data);

        $this->assertEquals($data, $payload->toArray());
    }
}
