<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchStaffRoleList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\StaffRoleData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStaffRoleList\Response as FetchStaffRoleListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchStaffRoleListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'staff_roles' => [
                    [
                        'role_id' => 3,
                        'role' => 'Первая линия поддержки',
                    ],
                    [
                        'role_id' => 9,
                        'role' => 'Вторая линия поддержки',
                    ],
                    [
                        'role_id' => 53,
                        'role' => 'Руководство',
                    ],
                ],
                'count' => 3,
            ],

            'expected' => new FetchStaffRoleListResponse(
                staffRoles: new Collection([
                    new StaffRoleData(
                        roleId: 3,
                        role: 'Первая линия поддержки'
                    ),
                    new StaffRoleData(
                        roleId: 9,
                        role: 'Вторая линия поддержки'
                    ),
                    new StaffRoleData(
                        roleId: 53,
                        role: 'Руководство'
                    ),
                ]),
                count: 3
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchStaffRoleListResponse $expected): void
    {
        $actual = FetchStaffRoleListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
