<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchUserList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\UserData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchUserList\Response as FetchUserListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchUserListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'users' => [
                    [
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
                    ],
                ],
                'total' => 1,
            ],

            'expected' => new FetchUserListResponse(
                users: new Collection([
                    new UserData(
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
                        ]
                    ),
                ]),
                total: 1
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchUserListResponse $expected): void
    {
        $actual = FetchUserListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
