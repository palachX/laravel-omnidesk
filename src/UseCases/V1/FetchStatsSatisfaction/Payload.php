<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchStatsSatisfaction;

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
     * @param  array<int>|int|Optional  $ratingId
     * @param  array<string>|string|Optional  $rating
     * @param  array<int>|int|Optional  $ratedStaffId
     * @param  array<int>|int|Optional  $ratedAssigneeRoleId
     * @param  array<int>|int|Optional  $participantId
     * @param  array<int>|int|Optional  $participantRoleId
     * @param  array<int>|int|Optional  $userId
     * @param  array<string>|string|Optional  $userEmail
     * @param  array<string>|string|Optional  $userPhone
     * @param  array<int>|int|Optional  $companyId
     * @param  array<int>|int|Optional  $groupId
     * @param  array<string>|string|Optional  $channel
     * @param  array<string>|string|Optional  $status
     * @param  array<string>|string|Optional  $priority
     * @param  array<mixed>|Optional  $labels
     * @param  array<mixed>|Optional  $customFields
     */
    public function __construct(
        #[Required]
        public readonly string $period,
        public readonly string|Optional $fromTime = new Optional,
        public readonly string|Optional $toTime = new Optional,
        public readonly array|int|Optional $ratingId = new Optional,
        public readonly array|string|Optional $rating = new Optional,
        public readonly bool|Optional $ratingComment = new Optional,
        public readonly array|int|Optional $ratedStaffId = new Optional,
        public readonly array|int|Optional $ratedAssigneeRoleId = new Optional,
        public readonly array|int|Optional $participantId = new Optional,
        public readonly array|int|Optional $participantRoleId = new Optional,
        public readonly array|int|Optional $userId = new Optional,
        public readonly array|string|Optional $userEmail = new Optional,
        public readonly array|string|Optional $userPhone = new Optional,
        public readonly string|Optional $initiator = new Optional,
        public readonly array|int|Optional $companyId = new Optional,
        public readonly array|int|Optional $groupId = new Optional,
        public readonly array|string|Optional $channel = new Optional,
        public readonly array|string|Optional $status = new Optional,
        public readonly array|string|Optional $priority = new Optional,
        public readonly array|Optional $labels = new Optional,
        public readonly array|Optional $customFields = new Optional,
        public readonly int|Optional $page = new Optional,
        public readonly int|Optional $limit = new Optional,
        public readonly string|Optional $sort = new Optional,
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
            'rating_id' => ['nullable'],
            'rating' => ['nullable'],
            'rating_comment' => ['nullable', 'boolean'],
            'rated_staff_id' => ['nullable'],
            'rated_assignee_role_id' => ['nullable'],
            'participant_id' => ['nullable'],
            'participant_role_id' => ['nullable'],
            'user_id' => ['nullable'],
            'user_email' => ['nullable'],
            'user_phone' => ['nullable'],
            'initiator' => ['nullable', 'string', 'in:user,agent'],
            'company_id' => ['nullable'],
            'group_id' => ['nullable'],
            'channel' => ['nullable'],
            'status' => ['nullable'],
            'priority' => ['nullable'],
            'labels' => ['nullable', 'array'],
            'custom_fields' => ['nullable', 'array'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'between:1,100'],
            'sort' => ['nullable', 'string', 'in:added_at_desc,added_at_asc'],
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
