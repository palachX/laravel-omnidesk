<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchStatsLeaderboard;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStatsLeaderboard\Payload as FetchStatsLeaderboardPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchStatsLeaderboardPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'period only' => [
            'payload' => [
                'period' => 'last_24_hours',
            ],
            'expected' => new FetchStatsLeaderboardPayload(
                period: 'last_24_hours',
            ),
        ];

        yield 'period with from_time and to_time' => [
            'payload' => [
                'period' => 'custom',
                'from_time' => '2023-06-01 00:00:00',
                'to_time' => '2023-06-01 23:59:59',
            ],
            'expected' => new FetchStatsLeaderboardPayload(
                period: 'custom',
                fromTime: '2023-06-01 00:00:00',
                toTime: '2023-06-01 23:59:59',
            ),
        ];

        yield 'period with group_id' => [
            'payload' => [
                'period' => 'last_7_days',
                'group_id' => 123,
            ],
            'expected' => new FetchStatsLeaderboardPayload(
                period: 'last_7_days',
                groupId: 123,
            ),
        ];

        yield 'period with multiple filters' => [
            'payload' => [
                'period' => 'this_month',
                'channel' => ['email', 'web'],
                'status' => 'open',
                'staff_id' => [456, 789],
                'company_id' => 100,
            ],
            'expected' => new FetchStatsLeaderboardPayload(
                period: 'this_month',
                channel: ['email', 'web'],
                status: 'open',
                staffId: [456, 789],
                companyId: 100,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $payload, FetchStatsLeaderboardPayload $expected): void
    {
        $actual = FetchStatsLeaderboardPayload::validateAndCreate($payload);
        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('dataProvider')]
    public function testToQuery(array $payload, FetchStatsLeaderboardPayload $expected): void
    {
        $payload = FetchStatsLeaderboardPayload::validateAndCreate($payload);

        $this->assertEquals($expected->toQuery(), $payload->toQuery());
    }
}
