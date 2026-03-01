<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreUser;

use Palach\Omnidesk\DTO\UserData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreUser\Response as StoreUserResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreUserResponseTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'email user response' => [
            'data' => [
                'user' => [
                    'user_id' => 200,
                    'user_full_name' => "User's full name",
                    'company_name' => "User's company name",
                    'company_position' => "User's position",
                    'thumbnail' => '',
                    'confirmed' => false,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'password' => 'bsdegs',
                    'type' => 'email',
                    'user_email' => 'user@domain.ru',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                    'linked_users' => [123, 456],
                ],
            ],

            'expected' => new StoreUserResponse(
                user: new UserData(
                    userId: 200,
                    userFullName: "User's full name",
                    companyName: "User's company name",
                    companyPosition: "User's position",
                    thumbnail: '',
                    confirmed: false,
                    active: true,
                    deleted: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    password: 'bsdegs',
                    type: 'email',
                    userEmail: 'user@domain.ru',
                    languageId: 2,
                    customFields: [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                    linkedUsers: [123, 456]
                )
            ),
        ];

        yield 'whatsapp user response' => [
            'data' => [
                'user' => [
                    'user_id' => 201,
                    'user_full_name' => 'John Doe',
                    'company_name' => 'Example Corp',
                    'company_position' => 'Developer',
                    'thumbnail' => '',
                    'confirmed' => true,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'password' => 'randompass',
                    'type' => 'whatsapp',
                    'user_whatsapp_phone' => '+79261234567',
                    'language_id' => 1,
                    'linked_users' => [],
                ],
            ],

            'expected' => new StoreUserResponse(
                user: new UserData(
                    userId: 201,
                    userFullName: 'John Doe',
                    companyName: 'Example Corp',
                    companyPosition: 'Developer',
                    thumbnail: '',
                    confirmed: true,
                    active: true,
                    deleted: false,
                    createdAt: 'Wed, 15 Jun 2023 14:30:00 +0300',
                    updatedAt: 'Wed, 15 Jun 2023 14:30:00 +0300',
                    password: 'randompass',
                    type: 'whatsapp',
                    userWhatsappPhone: '+79261234567',
                    languageId: 1,
                    linkedUsers: []
                )
            ),
        ];

        yield 'minimal user response' => [
            'data' => [
                'user' => [
                    'user_id' => 202,
                ],
            ],

            'expected' => new StoreUserResponse(
                user: new UserData(
                    userId: 202
                )
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testResponse(array $data, StoreUserResponse $expected): void
    {
        $response = StoreUserResponse::from($data);

        $this->assertEquals($expected, $response);
    }

    #[DataProvider('dataArrayProvider')]
    public function testResponseToArray(array $data, StoreUserResponse $expected): void
    {
        $response = StoreUserResponse::from($data);

        $this->assertEquals($data, $response->toArray());
    }
}
