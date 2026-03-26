<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchStatsLeaderboard;

use Palach\Omnidesk\Traits\HasQueryConversion;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data
{
    use HasQueryConversion;

    /**
     * @param  string  $period
     * @param  array<int>|int|Optional  $groupId
     * @param  array<string>|string|Optional  $channel
     * @param  array<string>|string|Optional  $status
     * @param  array<string>|string|Optional  $priority
     * @param  array<int>|int|Optional  $staffId
     * @param  array<int>|int|Optional  $assigneeRoleId
     * @param  array<int>|int|Optional  $participantId
     * @param  array<int>|int|Optional  $participantRoleId
     * @param  array<mixed>|Optional  $labels
     * @param  array<int>|int|Optional  $userId
     * @param  array<int>|int|Optional  $companyId
     * @param  array<mixed>|Optional  $customFields
     */
    public function __construct(
        #[Required]
        public readonly string $period,
        public readonly string|Optional $fromTime = new Optional,
        public readonly string|Optional $toTime = new Optional,
        public readonly array|int|Optional $groupId = new Optional,
        public readonly array|string|Optional $channel = new Optional,
        public readonly array|string|Optional $status = new Optional,
        public readonly array|string|Optional $priority = new Optional,
        public readonly array|int|Optional $staffId = new Optional,
        public readonly array|int|Optional $assigneeRoleId = new Optional,
        public readonly array|int|Optional $participantId = new Optional,
        public readonly array|int|Optional $participantRoleId = new Optional,
        public readonly array|Optional $labels = new Optional,
        public readonly string|Optional $initiator = new Optional,
        public readonly array|int|Optional $userId = new Optional,
        public readonly array|int|Optional $companyId = new Optional,
        public readonly array|Optional $customFields = new Optional,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'period' => ['required', 'string'],
            'from_time' => ['nullable', 'string'],
            'to_time' => ['nullable', 'string'],
            'group_id' => ['nullable'],
            'channel' => ['nullable'],
            'status' => ['nullable'],
            'priority' => ['nullable'],
            'staff_id' => ['nullable'],
            'assignee_role_id' => ['nullable'],
            'participant_id' => ['nullable'],
            'participant_role_id' => ['nullable'],
            'labels' => ['nullable', 'array'],
            'initiator' => ['nullable', 'string', 'in:user,agent'],
            'user_id' => ['nullable'],
            'company_id' => ['nullable'],
            'custom_fields' => ['nullable', 'array'],
        ];
    }

    /**
     * Serialization to meet the requirements of external Omnidesk API
     *
     * @return array<mixed>
     */
    public function toQuery(): array
    {
        $query = [];
        $data = $this->toArray();

        foreach ($data as $key => $datum) {
            if ($datum instanceof Optional) {
                continue;
            }
            if (is_array($datum)) {
                $this->serializeList($query, $key, $datum);
            } else {
                $query[$key] = $datum;
            }
        }

        return $query;
    }
}
