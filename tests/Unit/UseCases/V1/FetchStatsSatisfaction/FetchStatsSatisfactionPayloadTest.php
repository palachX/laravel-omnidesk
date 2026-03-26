<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchStatsSatisfaction;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStatsSatisfaction\Payload as FetchStatsSatisfactionPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchStatsSatisfactionPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'period only' => [
            'payload' => [
                'period' => 'last_24_hours',
            ],
            'expected' => new FetchStatsSatisfactionPayload(
                period: 'last_24_hours',
            ),
        ];

        yield 'period with from_time and to_time' => [
            'payload' => [
                'period' => 'custom',
                'from_time' => '2023-06-01 00:00:00',
                'to_time' => '2023-06-01 23:59:59',
            ],
            'expected' => new FetchStatsSatisfactionPayload(
                period: 'custom',
                fromTime: '2023-06-01 00:00:00',
                toTime: '2023-06-01 23:59:59',
            ),
        ];

        yield 'period with rating filters' => [
            'payload' => [
                'period' => 'last_7_days',
                'rating_id' => [1, 2, 3],
                'rating' => ['high', 'middle'],
                'rating_comment' => true,
            ],
            'expected' => new FetchStatsSatisfactionPayload(
                period: 'last_7_days',
                ratingId: [1, 2, 3],
                rating: ['high', 'middle'],
                ratingComment: true,
            ),
        ];

        yield 'period with staff filters' => [
            'payload' => [
                'period' => 'this_month',
                'rated_staff_id' => 193,
                'rated_assignee_role_id' => [1, 2],
                'participant_id' => [456, 789],
                'participant_role_id' => 3,
            ],
            'expected' => new FetchStatsSatisfactionPayload(
                period: 'this_month',
                ratedStaffId: 193,
                ratedAssigneeRoleId: [1, 2],
                participantId: [456, 789],
                participantRoleId: 3,
            ),
        ];

        yield 'period with user filters' => [
            'payload' => [
                'period' => 'last_30_days',
                'user_id' => 222760,
                'user_email' => ['test@example.com', 'user@example.com'],
                'user_phone' => '+1234567890',
            ],
            'expected' => new FetchStatsSatisfactionPayload(
                period: 'last_30_days',
                userId: 222760,
                userEmail: ['test@example.com', 'user@example.com'],
                userPhone: '+1234567890',
            ),
        ];

        yield 'period with case filters' => [
            'payload' => [
                'period' => 'today',
                'company_id' => 100,
                'group_id' => [318, 746],
                'channel' => ['email', 'web'],
                'status' => ['open', 'closed'],
                'priority' => ['normal', 'high'],
                'initiator' => 'user',
            ],
            'expected' => new FetchStatsSatisfactionPayload(
                period: 'today',
                companyId: 100,
                groupId: [318, 746],
                channel: ['email', 'web'],
                status: ['open', 'closed'],
                priority: ['normal', 'high'],
                initiator: 'user',
            ),
        ];

        yield 'period with pagination and sorting' => [
            'payload' => [
                'period' => 'this_week',
                'page' => 2,
                'limit' => 50,
                'sort' => 'added_at_desc',
            ],
            'expected' => new FetchStatsSatisfactionPayload(
                period: 'this_week',
                page: 2,
                limit: 50,
                sort: 'added_at_desc',
            ),
        ];

        yield 'period with custom fields and labels' => [
            'payload' => [
                'period' => 'last_month',
                'labels' => ['urgent', 'vip'],
                'custom_fields' => ['cf_7604' => 'text', 'cf_7605' => 1],
            ],
            'expected' => new FetchStatsSatisfactionPayload(
                period: 'last_month',
                labels: ['urgent', 'vip'],
                customFields: ['cf_7604' => 'text', 'cf_7605' => 1],
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $payload, FetchStatsSatisfactionPayload $expected): void
    {
        $actual = FetchStatsSatisfactionPayload::validateAndCreate($payload);
        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('dataProvider')]
    public function testToQuery(array $payload, FetchStatsSatisfactionPayload $expected): void
    {
        $payload = FetchStatsSatisfactionPayload::validateAndCreate($payload);

        $this->assertEquals($expected->toQuery(), $payload->toQuery());
    }
}
