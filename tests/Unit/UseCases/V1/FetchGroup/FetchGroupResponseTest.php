<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchGroup;

use Palach\Omnidesk\DTO\GroupData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchGroup\Response as FetchGroupResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchGroupResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full group data' => [
            'data' => [
                'group' => [
                    'group_id' => 200,
                    'group_title' => 'Test group',
                    'group_from_name' => 'Test group from name',
                    'group_signature' => 'Test group signature',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new FetchGroupResponse(
                group: new GroupData(
                    groupId: 200,
                    groupTitle: 'Test group',
                    groupFromName: 'Test group from name',
                    groupSignature: 'Test group signature',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];

        yield 'minimal group data' => [
            'data' => [
                'group' => [
                    'group_id' => 201,
                    'group_title' => 'Simple group',
                ],
            ],

            'expected' => new FetchGroupResponse(
                group: new GroupData(
                    groupId: 201,
                    groupTitle: 'Simple group',
                ),
            ),
        ];

        yield 'inactive group data' => [
            'data' => [
                'group' => [
                    'group_id' => 202,
                    'group_title' => 'Inactive group',
                    'active' => false,
                ],
            ],

            'expected' => new FetchGroupResponse(
                group: new GroupData(
                    groupId: 202,
                    groupTitle: 'Inactive group',
                    active: false,
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchGroupResponse $expected): void
    {
        $actual = FetchGroupResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
