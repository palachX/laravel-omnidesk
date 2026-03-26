<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\StatsLeaderboardData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchStatsLeaderboard\Payload as FetchStatsLeaderboardPayload;
use Palach\Omnidesk\UseCases\V1\FetchStatsLeaderboard\Response as FetchStatsLeaderboardResponse;
use Symfony\Component\Mailer\Exception\UnexpectedResponseException;

final readonly class StatisticsClient
{
    use ExtractsResponseData;

    private const string STATS_LEADERBOARD_URL = '/api/stats_leaderboard.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchStatsLeaderboard(FetchStatsLeaderboardPayload $payload): FetchStatsLeaderboardResponse
    {
        $response = $this->transport->get(self::STATS_LEADERBOARD_URL, $payload->toQuery());

        if (! is_array($response)) {
            throw new UnexpectedResponseException('Invalid response format');
        }

        $stats = collect($response)
            ->map(fn ($item) => StatsLeaderboardData::from($item['staff']));

        return new FetchStatsLeaderboardResponse(
            statsLeaderboard: $stats,
        );
    }
}
