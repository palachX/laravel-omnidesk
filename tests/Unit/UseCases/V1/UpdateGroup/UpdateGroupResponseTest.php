<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateGroup;

use Palach\Omnidesk\DTO\GroupData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateGroup\Response as UpdateGroupResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateGroupResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'group' => [
                    'group_id' => 200,
                    'group_title' => 'Test Group Updated',
                    'group_from_name' => 'Test Group From Name',
                    'group_signature' => 'Test Group Signature',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new UpdateGroupResponse(
                group: new GroupData(
                    groupId: 200,
                    groupTitle: 'Test Group Updated',
                    groupFromName: 'Test Group From Name',
                    groupSignature: 'Test Group Signature',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                )
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'group' => [
                    'group_id' => 201,
                    'group_title' => 'Minimal Group',
                    'active' => true,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Thu, 25 Dec 2014 15:30:00 +0200',
                ],
            ],

            'expected' => new UpdateGroupResponse(
                group: new GroupData(
                    groupId: 201,
                    groupTitle: 'Minimal Group',
                    active: true,
                    createdAt: 'Wed, 15 Jun 2023 14:30:00 +0300',
                    updatedAt: 'Thu, 25 Dec 2014 15:30:00 +0200',
                )
            ),
        ];

        yield 'inactive group' => [
            'data' => [
                'group' => [
                    'group_id' => 202,
                    'group_title' => 'Inactive Group',
                    'group_from_name' => 'Inactive From Name',
                    'group_signature' => 'Inactive Signature',
                    'active' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],

            'expected' => new UpdateGroupResponse(
                group: new GroupData(
                    groupId: 202,
                    groupTitle: 'Inactive Group',
                    groupFromName: 'Inactive From Name',
                    groupSignature: 'Inactive Signature',
                    active: false,
                    createdAt: 'Thu, 20 Jul 2023 09:15:00 +0300',
                    updatedAt: 'Fri, 26 Dec 2014 11:20:00 +0200',
                )
            ),
        ];

        yield 'group with only title' => [
            'data' => [
                'group' => [
                    'group_id' => 203,
                    'group_title' => 'Title Only Group',
                    'active' => true,
                    'created_at' => 'Fri, 25 Aug 2023 16:45:00 +0300',
                    'updated_at' => 'Sat, 27 Dec 2014 08:10:00 +0200',
                ],
            ],

            'expected' => new UpdateGroupResponse(
                group: new GroupData(
                    groupId: 203,
                    groupTitle: 'Title Only Group',
                    active: true,
                    createdAt: 'Fri, 25 Aug 2023 16:45:00 +0300',
                    updatedAt: 'Sat, 27 Dec 2014 08:10:00 +0200',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateGroupResponse $expected): void
    {
        $actual = UpdateGroupResponse::from($data);

        $this->assertEquals($expected, $actual);
    }
}
