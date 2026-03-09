<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchGroupList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\GroupData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchGroupList\Response as FetchGroupListResponse;

final class FetchGroupListResponseTest extends AbstractTestCase
{
    public function testResponse(): void
    {
        $groups = collect([
            new GroupData(
                groupId: 200,
                groupTitle: 'Test group',
                groupFromName: 'Test group from name',
                groupSignature: 'Test group signature',
                active: true,
                createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200'
            ),
            new GroupData(
                groupId: 202,
                groupTitle: 'Test group 2',
                groupFromName: 'Test group 2 from name',
                groupSignature: 'Test group 2 signature',
                active: false,
                createdAt: 'Mon, 15 May 2014 00:15:17 +0300',
                updatedAt: 'Tue, 13 Dec 2014 10:55:23 +0200'
            ),
        ]);

        $response = new FetchGroupListResponse(
            groups: $groups,
            total: 10
        );

        $this->assertEquals($groups, $response->groups);
        $this->assertEquals(10, $response->total);
    }

    public function testEmptyResponse(): void
    {
        $groups = new Collection;

        $response = new FetchGroupListResponse(
            groups: $groups,
            total: 0
        );

        $this->assertTrue($response->groups->isEmpty());
        $this->assertEquals(0, $response->total);
    }
}
