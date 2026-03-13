<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\StaffRoleData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStaffRoleList\Response as FetchStaffRoleListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchStaffRoleListTest extends AbstractTestCase
{
    private const string API_URL_STAFF_ROLES = '/api/staff_roles.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'response' => [
                '0' => [
                    'staff_roles' => [
                        'role_id' => 3,
                        'role' => 'Первая линия поддержки',
                    ],
                ],
                '1' => [
                    'staff_roles' => [
                        'role_id' => 9,
                        'role' => 'Вторая линия поддержки',
                    ],
                ],
                '2' => [
                    'staff_roles' => [
                        'role_id' => 53,
                        'role' => 'Руководство',
                    ],
                ],
                'count' => 3,
            ],
        ];
        yield 'not full data' => [
            'response' => [
                '0' => [
                    'staff_roles' => [
                        'role_id' => 3,
                        'role' => 'Первая линия поддержки',
                    ],
                ],
                'count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $response): void
    {
        $fullUrl = $this->host.self::API_URL_STAFF_ROLES;
        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->staffs()->fetchStaffRoleList();

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $count = isset($response['count']) ? (int) $response['count'] : 0;

        unset($response['count']);

        /**
         * @var array<int, array{staff_roles: array<string, mixed>}> $staffRolesRaw
         */
        $staffRolesRaw = array_values($response);

        $staffRoles = collect($staffRolesRaw)
            ->map(function (array $item) {
                return StaffRoleData::from($item['staff_roles']);
            });

        $this->assertEquals(new FetchStaffRoleListResponse(
            staffRoles: $staffRoles,
            count: $count
        ), $list);
    }
}
