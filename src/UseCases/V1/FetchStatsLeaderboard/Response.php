<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchStatsLeaderboard;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\StatsLeaderboardData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, StatsLeaderboardData>  $statsLeaderboard
     */
    public function __construct(
        public readonly Collection $statsLeaderboard,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'stats_leaderboard' => ['nullable', 'array'],
            'stats_leaderboard.*.staff_id' => ['required', 'integer'],
            'stats_leaderboard.*.staff_name' => ['required', 'string'],
            'stats_leaderboard.*.new_cases_in_total' => ['required', 'integer'],
            'stats_leaderboard.*.new_user_cases' => ['required', 'integer'],
            'stats_leaderboard.*.reopened_cases' => ['required', 'integer'],
            'stats_leaderboard.*.cases_being_handled' => ['required', 'integer'],
            'stats_leaderboard.*.cases_with_a_response' => ['required', 'integer'],
            'stats_leaderboard.*.first_response_time' => ['required', 'integer'],
            'stats_leaderboard.*.first_response_sla_violated' => ['required', 'string'],
            'stats_leaderboard.*.response_time' => ['required', 'integer'],
            'stats_leaderboard.*.response_sla_violated' => ['required', 'string'],
            'stats_leaderboard.*.response_writing_time' => ['required', 'integer'],
            'stats_leaderboard.*.total_number_of_responses' => ['required', 'integer'],
            'stats_leaderboard.*.total_number_of_notes' => ['required', 'integer'],
            'stats_leaderboard.*.number_of_responses_for_resolution' => ['required', 'integer'],
            'stats_leaderboard.*.closed_cases' => ['required', 'integer'],
            'stats_leaderboard.*.resolution_time' => ['required', 'integer'],
            'stats_leaderboard.*.resolution_sla_violated' => ['required', 'string'],
            'stats_leaderboard.*.ratings_of_responses' => ['required', 'string'],
        ];
    }
}
