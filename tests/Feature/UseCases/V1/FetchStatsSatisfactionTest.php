<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\StatsSatisfactionData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStatsSatisfaction\Payload as FetchStatsSatisfactionPayload;
use Palach\Omnidesk\UseCases\V1\FetchStatsSatisfaction\Response as FetchStatsSatisfactionResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchStatsSatisfactionTest extends AbstractTestCase
{
    private const string API_URL = '/api/stats_satisfaction.json';

    public static function dataProvider(): iterable
    {
        yield 'full satisfaction data' => [
            'payload' => [
                'period' => 'last_24_hours',
            ],
            'response' => [
                '0' => [
                    'rating_id' => 79,
                    'rating' => '3',
                    'rating_comment' => 'cool123',
                    'rated_staff_id' => 193,
                    'case_id' => 47232,
                    'case_number' => '882-935664',
                    'user_id' => 222760,
                    'staff_id' => 193,
                    'group_id' => 318,
                    'created_at' => 'Thu, 16 Jul 2020 15:00:35 +0300',
                    'updated_at' => 'Thu, 16 Jul 2020 15:00:35 +0300',
                ],
                '1' => [
                    'rating_id' => 78,
                    'rating' => '2',
                    'rating_comment' => '',
                    'rated_staff_id' => 0,
                    'case_id' => 47223,
                    'case_number' => '898-213499',
                    'user_id' => 213381,
                    'staff_id' => 193,
                    'group_id' => 746,
                    'created_at' => 'Thu, 16 Jul 2020 14:49:38 +0300',
                    'updated_at' => 'Thu, 16 Jul 2020 14:54:50 +0300',
                ],
                'total_count' => 8,
            ],
        ];

        yield 'with rating filters' => [
            'payload' => [
                'period' => 'this_month',
                'rating_id' => [1, 2, 3],
                'rating' => ['high', 'middle'],
                'rating_comment' => true,
            ],
            'response' => [
                '0' => [
                    'rating_id' => 80,
                    'rating' => '1',
                    'rating_comment' => 'excellent service!',
                    'rated_staff_id' => 456,
                    'case_id' => 47245,
                    'case_number' => '123-456789',
                    'user_id' => 333111,
                    'staff_id' => 456,
                    'group_id' => 999,
                    'created_at' => 'Fri, 17 Jul 2020 10:15:22 +0300',
                    'updated_at' => 'Fri, 17 Jul 2020 10:15:22 +0300',
                ],
                'total_count' => 1,
            ],
        ];

        yield 'with staff filters' => [
            'payload' => [
                'period' => 'last_7_days',
                'rated_staff_id' => 193,
                'rated_assignee_role_id' => [1, 2],
                'participant_id' => [456, 789],
                'participant_role_id' => 3,
            ],
            'response' => [
                '0' => [
                    'rating_id' => 79,
                    'rating' => '3',
                    'rating_comment' => 'cool123',
                    'rated_staff_id' => 193,
                    'case_id' => 47232,
                    'case_number' => '882-935664',
                    'user_id' => 222760,
                    'staff_id' => 193,
                    'group_id' => 318,
                    'created_at' => 'Thu, 16 Jul 2020 15:00:35 +0300',
                    'updated_at' => 'Thu, 16 Jul 2020 15:00:35 +0300',
                ],
                'total_count' => 5,
            ],
        ];

        yield 'with user filters' => [
            'payload' => [
                'period' => 'last_30_days',
                'user_id' => 222760,
                'user_email' => ['test@example.com', 'user@example.com'],
                'user_phone' => '+1234567890',
            ],
            'response' => [
                '0' => [
                    'rating_id' => 79,
                    'rating' => '3',
                    'rating_comment' => 'cool123',
                    'rated_staff_id' => 193,
                    'case_id' => 47232,
                    'case_number' => '882-935664',
                    'user_id' => 222760,
                    'staff_id' => 193,
                    'group_id' => 318,
                    'created_at' => 'Thu, 16 Jul 2020 15:00:35 +0300',
                    'updated_at' => 'Thu, 16 Jul 2020 15:00:35 +0300',
                ],
                'total_count' => 3,
            ],
        ];

        yield 'with case filters and pagination' => [
            'payload' => [
                'period' => 'today',
                'company_id' => 100,
                'group_id' => [318, 746],
                'channel' => ['email', 'web'],
                'status' => ['open', 'closed'],
                'priority' => ['normal', 'high'],
                'initiator' => 'user',
                'page' => 2,
                'limit' => 50,
                'sort' => 'added_at_desc',
            ],
            'response' => [
                '0' => [
                    'rating_id' => 78,
                    'rating' => '2',
                    'rating_comment' => '',
                    'rated_staff_id' => 0,
                    'case_id' => 47223,
                    'case_number' => '898-213499',
                    'user_id' => 213381,
                    'staff_id' => 193,
                    'group_id' => 746,
                    'created_at' => 'Thu, 16 Jul 2020 14:49:38 +0300',
                    'updated_at' => 'Thu, 16 Jul 2020 14:54:50 +0300',
                ],
                'total_count' => 15,
            ],
        ];

        yield 'custom period' => [
            'payload' => [
                'period' => 'custom',
                'from_time' => '2023-06-01 00:00:00',
                'to_time' => '2023-06-01 23:59:59',
            ],
            'response' => [
                '0' => [
                    'rating_id' => 81,
                    'rating' => '1',
                    'rating_comment' => 'great support!',
                    'rated_staff_id' => 789,
                    'case_id' => 47250,
                    'case_number' => '999-888777',
                    'user_id' => 444222,
                    'staff_id' => 789,
                    'group_id' => 555,
                    'created_at' => 'Mon, 01 Jun 2023 12:30:45 +0300',
                    'updated_at' => 'Mon, 01 Jun 2023 12:30:45 +0300',
                ],
                'total_count' => 1,
            ],
        ];

        yield 'empty data' => [
            'payload' => [
                'period' => 'custom',
            ],
            'response' => [
                'total_count' => 0,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchStatsSatisfactionPayload::from($payload);

        $fullUrl = $this->host.self::API_URL;
        $query = http_build_query($payload->toQuery(), '', '&', PHP_QUERY_RFC3986);
        if ($query !== '') {
            $fullUrl .= '?'.$query;
        }

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $statsSatisfaction = $this->makeHttpClient()->statistics()->fetchStatsSatisfaction($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $totalCount = $response['total_count'] ?? 0;
        unset($response['total_count']);

        $stats = collect($response)
            ->map(fn ($item) => StatsSatisfactionData::from($item));

        $this->assertEquals(
            new FetchStatsSatisfactionResponse(
                statsSatisfaction: $stats,
                totalCount: $totalCount,
            ),
            $statsSatisfaction
        );
    }
}
