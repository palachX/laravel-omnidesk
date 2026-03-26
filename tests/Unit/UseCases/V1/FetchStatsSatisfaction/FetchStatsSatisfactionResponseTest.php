<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchStatsSatisfaction;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\StatsSatisfactionData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStatsSatisfaction\Response as FetchStatsSatisfactionResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchStatsSatisfactionResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single rating with comment' => [
            'response' => [
                'stats_satisfaction' => [
                    [
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
                ],
                'total_count' => 8,
            ],
            'expected' => new FetchStatsSatisfactionResponse(
                statsSatisfaction: new Collection([
                    new StatsSatisfactionData(
                        ratingId: 79,
                        rating: '3',
                        ratingComment: 'cool123',
                        ratedStaffId: 193,
                        caseId: 47232,
                        caseNumber: '882-935664',
                        userId: 222760,
                        staffId: 193,
                        groupId: 318,
                        createdAt: 'Thu, 16 Jul 2020 15:00:35 +0300',
                        updatedAt: 'Thu, 16 Jul 2020 15:00:35 +0300',
                    ),
                ]),
                totalCount: 8,
            ),
        ];

        yield 'rating without comment (case rating)' => [
            'response' => [
                'stats_satisfaction' => [
                    [
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
                ],
                'total_count' => 1,
            ],
            'expected' => new FetchStatsSatisfactionResponse(
                statsSatisfaction: new Collection([
                    new StatsSatisfactionData(
                        ratingId: 78,
                        rating: '2',
                        ratedStaffId: 0,
                        caseId: 47223,
                        caseNumber: '898-213499',
                        userId: 213381,
                        staffId: 193,
                        groupId: 746,
                        createdAt: 'Thu, 16 Jul 2020 14:49:38 +0300',
                        updatedAt: 'Thu, 16 Jul 2020 14:54:50 +0300',
                        ratingComment: '',
                    ),
                ]),
                totalCount: 1,
            ),
        ];

        yield 'multiple ratings' => [
            'response' => [
                'stats_satisfaction' => [
                    [
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
                    [
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
                    [
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
                ],
                'total_count' => 15,
            ],
            'expected' => new FetchStatsSatisfactionResponse(
                statsSatisfaction: new Collection([
                    new StatsSatisfactionData(
                        ratingId: 79,
                        rating: '3',
                        ratedStaffId: 193,
                        caseId: 47232,
                        caseNumber: '882-935664',
                        userId: 222760,
                        staffId: 193,
                        groupId: 318,
                        createdAt: 'Thu, 16 Jul 2020 15:00:35 +0300',
                        updatedAt: 'Thu, 16 Jul 2020 15:00:35 +0300',
                        ratingComment: 'cool123',
                    ),
                    new StatsSatisfactionData(
                        ratingId: 78,
                        rating: '2',
                        ratedStaffId: 0,
                        caseId: 47223,
                        caseNumber: '898-213499',
                        userId: 213381,
                        staffId: 193,
                        groupId: 746,
                        createdAt: 'Thu, 16 Jul 2020 14:49:38 +0300',
                        updatedAt: 'Thu, 16 Jul 2020 14:54:50 +0300',
                        ratingComment: '',
                    ),
                    new StatsSatisfactionData(
                        ratingId: 80,
                        rating: '1',
                        ratedStaffId: 456,
                        caseId: 47245,
                        caseNumber: '123-456789',
                        userId: 333111,
                        staffId: 456,
                        groupId: 999,
                        createdAt: 'Fri, 17 Jul 2020 10:15:22 +0300',
                        updatedAt: 'Fri, 17 Jul 2020 10:15:22 +0300',
                        ratingComment: 'excellent service!',
                    ),
                ]),
                totalCount: 15,
            ),
        ];

        yield 'empty stats satisfaction' => [
            'response' => [
                'stats_satisfaction' => [],
                'total_count' => 0,
            ],
            'expected' => new FetchStatsSatisfactionResponse(
                statsSatisfaction: new Collection([]),
                totalCount: 0,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $response, FetchStatsSatisfactionResponse $expected): void
    {
        $actual = FetchStatsSatisfactionResponse::validateAndCreate($response);
        $this->assertEquals($expected, $actual);
    }
}
