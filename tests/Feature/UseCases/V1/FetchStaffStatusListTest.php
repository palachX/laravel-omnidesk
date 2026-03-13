<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\StaffStatusData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStaffStatusList\Response as FetchStaffStatusListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchStaffStatusListTest extends AbstractTestCase
{
    private const string API_URL_STAFF_STATUSES = '/api/staff_statuses.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'response' => [
                '0' => [
                    'staff_statuses' => [
                        'status_id' => 1,
                        'status' => 'Онлайн',
                        'active' => true,
                    ],
                ],
                '1' => [
                    'staff_statuses' => [
                        'status_id' => 3,
                        'status' => 'Без чатов',
                        'active' => true,
                    ],
                ],
                '2' => [
                    'staff_statuses' => [
                        'status_id' => 113808,
                        'status' => 'Обучение',
                        'active' => false,
                    ],
                ],
                '3' => [
                    'staff_statuses' => [
                        'status_id' => 120273,
                        'status' => 'Обед',
                        'active' => false,
                    ],
                ],
                '4' => [
                    'staff_statuses' => [
                        'status_id' => 2,
                        'status' => 'Офлайн',
                        'active' => true,
                    ],
                ],
                'count' => 5,
            ],
        ];
        yield 'not full data' => [
            'response' => [
                '0' => [
                    'staff_statuses' => [
                        'status_id' => 1,
                        'status' => 'Онлайн',
                        'active' => true,
                    ],
                ],
                'count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $response): void
    {
        $fullUrl = $this->host.self::API_URL_STAFF_STATUSES;
        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->staffs()->fetchStaffStatusList();

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $count = isset($response['count']) ? (int) $response['count'] : 0;

        unset($response['count']);

        /**
         * @var array<int, array{staff_statuses: array<string, mixed>}> $staffStatusesRaw
         */
        $staffStatusesRaw = array_values($response);

        $staffStatuses = collect($staffStatusesRaw)
            ->map(function (array $item) {
                return StaffStatusData::from($item['staff_statuses']);
            });

        $this->assertEquals(new FetchStaffStatusListResponse(
            staffStatuses: $staffStatuses,
            count: $count
        ), $list);
    }
}
