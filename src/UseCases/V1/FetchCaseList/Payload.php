<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchCaseList;

use Palach\Omnidesk\Traits\HasQueryConversion;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data
{
    use HasQueryConversion;

    /**
     * @param  array<string>|Optional  $status
     * @param  array<string>|Optional  $channel
     * @param  array<string>|Optional  $userCustomId
     */
    public function __construct(
        public readonly array|Optional $status = new Optional,
        public readonly array|Optional $channel = new Optional,
        public readonly array|Optional $userCustomId = new Optional,
        /**
         * Default in omnidesk 1
         */
        #[Max(500)]
        #[Min(1)]
        public readonly int|Optional $page = new Optional,
        /**
         * Default in omnidesk 100
         */
        #[Max(100)]
        #[Min(1)]
        public readonly int|Optional $limit = new Optional,
        /**
         * Default in omnidesk false
         */
        public readonly bool|Optional $showActiveChats = new Optional,
    ) {}

    /**
     * Serialization to meet the requirements of the external Omnidesk API
     *
     * @return array<mixed>
     */
    public function toQuery(): array
    {
        $query = [];

        $this->serializeList($query, 'channel', $this->channel);
        $this->serializeList($query, 'status', $this->status);
        $this->serializeList($query, 'user_custom_id', $this->userCustomId);

        if (! $this->page instanceof Optional) {
            $query['page'] = $this->page;
        }

        if (! $this->limit instanceof Optional) {
            $query['limit'] = $this->limit;
        }

        if (! $this->showActiveChats instanceof Optional) {
            $query['show_active_chats'] = $this->showActiveChats;
        }

        return $query;
    }
}
