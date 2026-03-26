<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\StatsLeaderboardData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStatsLeaderboard\Payload as FetchStatsLeaderboardPayload;
use Palach\Omnidesk\UseCases\V1\FetchStatsLeaderboard\Response as FetchStatsLeaderboardResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchStatsLeaderboardTest extends AbstractTestCase
{
    private const string API_URL = '/api/stats_leaderboard.json';

    public static function dataProvider(): iterable
    {
        yield 'full stats data' => [
            'payload' => [
                'period' => 'last_24_hours',
            ],
            'response' => [
                '0' => [
                    'staff' => [
                        'staff_id' => 42047,
                        'staff_name' => 'John Snow',
                        'new_cases_in_total' => 21,
                        'new_user_cases' => 20,
                        'reopened_cases' => 2,
                        'cases_being_handled' => 25,
                        'cases_with_a_response' => 21,
                        'first_response_time' => 28,
                        'first_response_sla_violated' => '0%',
                        'response_time' => 49,
                        'response_sla_violated' => '0%',
                        'response_writing_time' => 19,
                        'total_number_of_responses' => 29,
                        'total_number_of_notes' => 8,
                        'number_of_responses_for_resolution' => 2,
                        'closed_cases' => 19,
                        'resolution_time' => 516,
                        'resolution_sla_violated' => '0%',
                        'ratings_of_responses' => '-',
                    ],
                ],
                '1' => [
                    'staff' => [
                        'staff_id' => 50482,
                        'staff_name' => 'Jack Sparrow',
                        'new_cases_in_total' => 58,
                        'new_user_cases' => 57,
                        'reopened_cases' => 7,
                        'cases_being_handled' => 103,
                        'cases_with_a_response' => 64,
                        'first_response_time' => 97,
                        'first_response_sla_violated' => '0%',
                        'response_time' => 80,
                        'response_sla_violated' => '0.9%',
                        'response_writing_time' => 38,
                        'total_number_of_responses' => 202,
                        'total_number_of_notes' => 18,
                        'number_of_responses_for_resolution' => 3,
                        'closed_cases' => 73,
                        'resolution_time' => 825,
                        'resolution_sla_violated' => '21.9%',
                        'ratings_of_responses' => '72%',
                    ],
                ],
            ],
        ];

        yield 'with filters' => [
            'payload' => [
                'period' => 'this_month',
                'group_id' => 123,
                'channel' => ['email', 'web'],
                'status' => 'open',
                'staff_id' => [456, 789],
            ],
            'response' => [
                '0' => [
                    'staff' => [
                        'staff_id' => 42047,
                        'staff_name' => 'John Snow',
                        'new_cases_in_total' => 21,
                        'new_user_cases' => 20,
                        'reopened_cases' => 2,
                        'cases_being_handled' => 25,
                        'cases_with_a_response' => 21,
                        'first_response_time' => 28,
                        'first_response_sla_violated' => '0%',
                        'response_time' => 49,
                        'response_sla_violated' => '0%',
                        'response_writing_time' => 19,
                        'total_number_of_responses' => 29,
                        'total_number_of_notes' => 8,
                        'number_of_responses_for_resolution' => 2,
                        'closed_cases' => 19,
                        'resolution_time' => 516,
                        'resolution_sla_violated' => '0%',
                        'ratings_of_responses' => '-',
                    ],
                ],
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
                    'staff' => [
                        'staff_id' => 42047,
                        'staff_name' => 'John Snow',
                        'new_cases_in_total' => 21,
                        'new_user_cases' => 20,
                        'reopened_cases' => 2,
                        'cases_being_handled' => 25,
                        'cases_with_a_response' => 21,
                        'first_response_time' => 28,
                        'first_response_sla_violated' => '0%',
                        'response_time' => 49,
                        'response_sla_violated' => '0%',
                        'response_writing_time' => 19,
                        'total_number_of_responses' => 29,
                        'total_number_of_notes' => 8,
                        'number_of_responses_for_resolution' => 2,
                        'closed_cases' => 19,
                        'resolution_time' => 516,
                        'resolution_sla_violated' => '0%',
                        'ratings_of_responses' => '-',
                    ],
                ],
            ],
        ];

        yield 'empty data' => [
            'payload' => [
                'period' => 'custom',
            ],
            'response' => [],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchStatsLeaderboardPayload::from($payload);

        $fullUrl = $this->host.self::API_URL;
        $query = http_build_query($payload->toQuery(), '', '&', PHP_QUERY_RFC3986);
        if ($query !== '') {
            $fullUrl .= '?'.$query;
        }

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $statsLeaderboard = $this->makeHttpClient()->statistics()->fetchStatsLeaderboard($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $stats = collect($response)
            ->map(fn ($item) => StatsLeaderboardData::from($item['staff']));

        $this->assertEquals(
            new FetchStatsLeaderboardResponse(statsLeaderboard: $stats),
            $statsLeaderboard
        );
    }
}
