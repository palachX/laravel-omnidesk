<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\CaseData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCaseList\Payload as FetchCaseListPayload;
use Palach\Omnidesk\UseCases\V1\FetchCaseList\Response as FetchCaseListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchCaseListTest extends AbstractTestCase
{
    private const string API_URL_CASES = '/api/cases.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'payload' => [
                'page' => 1,
                'limit' => 10,
                'status' => ['open', 'closed'],
                'channel' => ['chh200'],
                'user_custom_id' => ['8e334869-a6ca-41da-b5cd-a8a51f99a529'],
            ],
            'response' => [
                [
                    'case' => [
                        'case_id' => 2000,
                        'case_number' => '664-245651',
                        'subject' => 'Договор и счёт',
                        'user_id' => 123,
                        'staff_id' => 321,
                        'group_id' => 444,
                        'status' => 'open',
                        'priority' => 'normal',
                        'channel' => 'chh21',
                        'deleted' => false,
                        'spam' => false,
                    ],
                ],
                [
                    'case' => [
                        'case_id' => 2001,
                        'case_number' => '664-245652',
                        'subject' => 'Договор и счёт - 2',
                        'user_id' => 123,
                        'staff_id' => 321,
                        'group_id' => 444,
                        'status' => 'closed',
                        'priority' => 'normal',
                        'channel' => 'chh21',
                        'deleted' => true,
                        'spam' => false,
                    ],
                ],
                'total_count' => 2,
            ],
        ];
        yield 'not full data' => [
            'payload' => [
                'status' => ['open'],
                'channel' => ['chh200'],
                'user_custom_id' => ['8e334869-a6ca-41da-b5cd-a8a51f99a529'],
            ],
            'response' => [
                [
                    'case' => [
                        'case_id' => 2000,
                        'case_number' => '664-245651',
                        'subject' => 'Договор и счёт',
                        'user_id' => 123,
                        'staff_id' => 321,
                        'group_id' => 444,
                        'status' => 'open',
                        'priority' => 'normal',
                        'channel' => 'chh200',
                        'deleted' => false,
                        'spam' => false,
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchCaseListPayload::from($payload);

        $url = self::API_URL_CASES;
        $query = http_build_query($payload->toQuery());
        $fullUrl = $this->host.$url.'?'.$query;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->cases()->fetchList(FetchCaseListPayload::from($payload));

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{case: array<string, mixed>}> $casesRaw
         */
        $casesRaw = array_values($response);

        $cases = collect($casesRaw)
            ->map(function (array $item) {
                return CaseData::from($item['case']);
            });

        $this->assertEquals(new FetchCaseListResponse(
            cases: $cases,
            total: $total
        ), $list);
    }
}
