<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\LinkUser;

use Palach\Omnidesk\DTO\UserData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\LinkUser\Response as LinkUserResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class LinkUserResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'user' => [
                    'user_id' => 1307386,
                    'user_full_name' => "User's full name",
                    'company_name' => "User's company name",
                    'company_position' => "User's position",
                    'thumbnail' => '',
                    'confirmed' => false,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2020 10:55:23 +0200',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                    'type' => 'telegram',
                    'telegram_id' => '495582869',
                    'linked_users' => [1307386, 25830712],
                ],
            ],
            'expected' => new LinkUserResponse(
                user: new UserData(
                    userId: 1307386,
                    userFullName: "User's full name",
                    companyName: "User's company name",
                    companyPosition: "User's position",
                    thumbnail: '',
                    confirmed: false,
                    active: true,
                    deleted: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2020 10:55:23 +0200',
                    type: 'telegram',
                    telegramId: '495582869',
                    languageId: 2,
                    customFields: [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                    linkedUsers: [1307386, 25830712],
                ),
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'user' => [
                    'user_id' => 1307386,
                    'user_full_name' => 'Test User',
                    'confirmed' => true,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2020 10:55:23 +0200',
                ],
            ],
            'expected' => new LinkUserResponse(
                user: new UserData(
                    userId: 1307386,
                    userFullName: 'Test User',
                    confirmed: true,
                    active: true,
                    deleted: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2020 10:55:23 +0200',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, LinkUserResponse $expected): void
    {
        $actual = LinkUserResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
