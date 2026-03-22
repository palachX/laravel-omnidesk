<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisableGroup;

use Palach\Omnidesk\DTO\GroupData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableGroup\Response as DisabledGroupResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisableGroupResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'disable group response' => [
            'data' => [
                'group' => [
                    'group_id' => 200,
                    'group_title' => 'Test group 2',
                    'group_from_name' => 'Test group 2 from name',
                    'group_signature' => 'Test group 2 signature',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new DisabledGroupResponse(
                group: new GroupData(
                    groupId: 200,
                    groupTitle: 'Test group 2',
                    groupFromName: 'Test group 2 from name',
                    groupSignature: 'Test group 2 signature',
                    active: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DisabledGroupResponse $expected): void
    {
        $actual = DisabledGroupResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
