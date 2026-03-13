<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchStaffStatusList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\StaffStatusData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStaffStatusList\Response as FetchStaffStatusListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchStaffStatusListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'staff_statuses' => [
                    [
                        'status_id' => 1,
                        'status' => 'Онлайн',
                        'active' => true,
                    ],
                    [
                        'status_id' => 3,
                        'status' => 'Без чатов',
                        'active' => true,
                    ],
                    [
                        'status_id' => 2,
                        'status' => 'Офлайн',
                        'active' => true,
                    ],
                ],
                'count' => 3,
            ],

            'expected' => new FetchStaffStatusListResponse(
                staffStatuses: new Collection([
                    new StaffStatusData(
                        statusId: 1,
                        status: 'Онлайн',
                        active: true
                    ),
                    new StaffStatusData(
                        statusId: 3,
                        status: 'Без чатов',
                        active: true
                    ),
                    new StaffStatusData(
                        statusId: 2,
                        status: 'Офлайн',
                        active: true
                    ),
                ]),
                count: 3
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchStaffStatusListResponse $expected): void
    {
        $actual = FetchStaffStatusListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
