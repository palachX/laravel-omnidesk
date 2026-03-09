<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreGroup;

use Palach\Omnidesk\DTO\GroupData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreGroup\Response as StoreGroupResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreGroupResponseTest extends AbstractTestCase
{
    public static function responseDataProvider(): iterable
    {
        yield 'full response' => [
            'response' => [
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
            'expected' => new StoreGroupResponse(
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
    }

    #[DataProvider('responseDataProvider')]
    public function testFrom(array $response, StoreGroupResponse $expected): void
    {
        $actual = StoreGroupResponse::from($response);

        $this->assertEquals($expected, $actual);
    }
}
