<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\GroupData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchGroupList\Payload as FetchGroupListPayload;
use Palach\Omnidesk\UseCases\V1\FetchGroupList\Response as FetchGroupListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchGroupListTest extends AbstractTestCase
{
    private const string API_URL_GROUPS = '/api/groups.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'payload' => [
                'page' => 1,
                'limit' => 2,
            ],
            'response' => [
                [
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
                [
                    'group' => [
                        'group_id' => 202,
                        'group_title' => 'Test group 2',
                        'group_from_name' => 'Test group 2 from name',
                        'group_signature' => 'Test group 2 signature',
                        'active' => false,
                        'created_at' => 'Mon, 15 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 13 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total_count' => 10,
            ],
        ];
        yield 'default values' => [
            'payload' => [
                'page' => 1,
                'limit' => 1,
            ],
            'response' => [
                [
                    'group' => [
                        'group_id' => 200,
                        'group_title' => 'Test group',
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchGroupListPayload::from($payload);

        $url = self::API_URL_GROUPS;
        $query = http_build_query($payload->toQuery());
        $fullUrl = $this->host.$url.'?'.$query;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->groups()->fetchList(FetchGroupListPayload::from($payload));

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{group: array<string, mixed>}> $groupsRaw
         */
        $groupsRaw = array_values($response);

        $groups = collect($groupsRaw)
            ->map(function (array $item) {
                return GroupData::from($item['group']);
            });

        $this->assertEquals(new FetchGroupListResponse(
            groups: $groups,
            total: $total
        ), $list);
    }
}
