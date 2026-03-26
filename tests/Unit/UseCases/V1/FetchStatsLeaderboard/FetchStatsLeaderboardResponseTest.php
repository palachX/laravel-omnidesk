<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchStatsLeaderboard;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\StatsLeaderboardData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStatsLeaderboard\Response as FetchStatsLeaderboardResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchStatsLeaderboardResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single staff stats' => [
            'response' => [
                'stats_leaderboard' => [
                    [
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
            'expected' => new FetchStatsLeaderboardResponse(
                statsLeaderboard: new Collection([
                    new StatsLeaderboardData(
                        staffId: 42047,
                        staffName: 'John Snow',
                        newCasesInTotal: 21,
                        newUserCases: 20,
                        reopenedCases: 2,
                        casesBeingHandled: 25,
                        casesWithAResponse: 21,
                        firstResponseTime: 28,
                        firstResponseSlaViolated: '0%',
                        responseTime: 49,
                        responseSlaViolated: '0%',
                        responseWritingTime: 19,
                        totalNumberOfResponses: 29,
                        totalNumberOfNotes: 8,
                        numberOfResponsesForResolution: 2,
                        closedCases: 19,
                        resolutionTime: 516,
                        resolutionSlaViolated: '0%',
                        ratingsOfResponses: '-',
                    ),
                ]),
            ),
        ];

        yield 'multiple staff stats' => [
            'response' => [
                'stats_leaderboard' => [
                    [
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
                    [
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
            'expected' => new FetchStatsLeaderboardResponse(
                statsLeaderboard: new Collection([
                    new StatsLeaderboardData(
                        staffId: 42047,
                        staffName: 'John Snow',
                        newCasesInTotal: 21,
                        newUserCases: 20,
                        reopenedCases: 2,
                        casesBeingHandled: 25,
                        casesWithAResponse: 21,
                        firstResponseTime: 28,
                        firstResponseSlaViolated: '0%',
                        responseTime: 49,
                        responseSlaViolated: '0%',
                        responseWritingTime: 19,
                        totalNumberOfResponses: 29,
                        totalNumberOfNotes: 8,
                        numberOfResponsesForResolution: 2,
                        closedCases: 19,
                        resolutionTime: 516,
                        resolutionSlaViolated: '0%',
                        ratingsOfResponses: '-',
                    ),
                    new StatsLeaderboardData(
                        staffId: 50482,
                        staffName: 'Jack Sparrow',
                        newCasesInTotal: 58,
                        newUserCases: 57,
                        reopenedCases: 7,
                        casesBeingHandled: 103,
                        casesWithAResponse: 64,
                        firstResponseTime: 97,
                        firstResponseSlaViolated: '0%',
                        responseTime: 80,
                        responseSlaViolated: '0.9%',
                        responseWritingTime: 38,
                        totalNumberOfResponses: 202,
                        totalNumberOfNotes: 18,
                        numberOfResponsesForResolution: 3,
                        closedCases: 73,
                        resolutionTime: 825,
                        resolutionSlaViolated: '21.9%',
                        ratingsOfResponses: '72%',
                    ),
                ]),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $response, FetchStatsLeaderboardResponse $expected): void
    {
        $actual = FetchStatsLeaderboardResponse::validateAndCreate($response);
        $this->assertEquals($expected, $actual);
    }
}
