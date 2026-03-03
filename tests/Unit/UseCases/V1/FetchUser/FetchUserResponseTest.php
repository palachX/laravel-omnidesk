<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchUser;

use Palach\Omnidesk\DTO\UserData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchUser\Response as FetchUserResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchUserResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
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
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0300',
                    'last_contact_at' => 'Tue, 27 Dec 2014 11:52:09 +0300',
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

            'expected' => new FetchUserResponse(
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
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0300',
                    type: 'email',
                    userEmail: 'user@domain.ru',
                    languageId: 2,
                    customFields: [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                    linkedUsers: [123, 456],
                )
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'user' => [
                    'user_id' => 201,
                    'user_full_name' => 'John Doe',
                    'company_name' => 'Acme Inc',
                    'company_position' => 'Developer',
                    'thumbnail' => 'https://example.com/avatar.jpg',
                    'confirmed' => true,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Wed, 15 Jan 2020 10:30:00 +0300',
                    'updated_at' => 'Fri, 20 Dec 2024 15:45:30 +0300',
                    'type' => 'email',
                    'user_email' => 'john.doe@example.com',
                    'language_id' => 1,
                ],
            ],

            'expected' => new FetchUserResponse(
                user: new UserData(
                    userId: 201,
                    userFullName: 'John Doe',
                    companyName: 'Acme Inc',
                    companyPosition: 'Developer',
                    thumbnail: 'https://example.com/avatar.jpg',
                    confirmed: true,
                    active: true,
                    deleted: false,
                    createdAt: 'Wed, 15 Jan 2020 10:30:00 +0300',
                    updatedAt: 'Fri, 20 Dec 2024 15:45:30 +0300',
                    type: 'email',
                    userEmail: 'john.doe@example.com',
                    languageId: 1,
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchUserResponse $expected): void
    {
        $actual = FetchUserResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
