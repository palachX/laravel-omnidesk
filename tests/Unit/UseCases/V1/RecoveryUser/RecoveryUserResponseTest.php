<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\RecoveryUser;

use Palach\Omnidesk\DTO\UserData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\RecoveryUser\Response as RecoveryUserResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class RecoveryUserResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'recovery user response' => [
            'data' => [
                'user' => [
                    'user_id' => 200,
                    'user_full_name' => "User's full name changed",
                    'company_name' => "User's company name",
                    'company_position' => "User's position",
                    'thumbnail' => '',
                    'confirmed' => false,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'type' => 'email',
                    'user_email' => 'user@domain.ru',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                ],
            ],
            'expected' => new RecoveryUserResponse(
                user: new UserData(
                    userId: 200,
                    userFullName: "User's full name changed",
                    companyName: "User's company name",
                    companyPosition: "User's position",
                    thumbnail: '',
                    confirmed: false,
                    active: true,
                    deleted: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    type: 'email',
                    userEmail: 'user@domain.ru',
                    languageId: 2,
                    customFields: [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, RecoveryUserResponse $expected): void
    {
        $actual = RecoveryUserResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
