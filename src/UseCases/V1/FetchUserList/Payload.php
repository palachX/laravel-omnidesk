<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchUserList;

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
     * @param  array<string>|Optional  $customFields
     * @param  array<int>|Optional  $companyId
     */
    public function __construct(
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
        public readonly string|Optional $userEmail = new Optional,
        public readonly string|Optional $userPhone = new Optional,
        public readonly string|Optional $userCustomId = new Optional,
        public readonly string|Optional $userCustomChannel = new Optional,
        public readonly array|Optional $companyId = new Optional,
        public readonly int|Optional $languageId = new Optional,
        public readonly array|Optional $customFields = new Optional,
        public readonly bool|Optional $amountOfCases = new Optional,
        public readonly string|Optional $fromTime = new Optional,
        public readonly string|Optional $toTime = new Optional,
        public readonly string|Optional $fromUpdatedTime = new Optional,
        public readonly string|Optional $toUpdatedTime = new Optional,
        public readonly string|Optional $fromLastContactTime = new Optional,
        public readonly string|Optional $toLastContactTime = new Optional,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toQuery(): array
    {
        $query = [];

        if (! $this->companyId instanceof Optional) {
            $this->serializeList($query, 'company_id', $this->companyId);

            $query['company_id'] = is_array($query['company_id'])
                ? array_map(static fn ($v): string => (string) $v, $query['company_id'])
                : (string) $query['company_id'];
        }

        foreach ($this->toArray() as $key => $value) {
            if ($key === 'company_id') {
                continue;
            }

            $query[$key] = $value;
        }

        return $query;
    }
}
