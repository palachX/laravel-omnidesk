<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchCaseChangelog;

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
     * @param  int  $caseId
     * @param  int[]|Optional  $rules
     */
    public function __construct(
        #[Required]
        public readonly int $caseId,
        public readonly string|Optional $staff = new Optional,
        public readonly string|Optional $status = new Optional,
        public readonly bool|Optional $showChatActivation = new Optional,
        public readonly string|Optional $fixedChatStaff = new Optional,
        public readonly bool|Optional $showChatCompletion = new Optional,
        public readonly string|Optional $subject = new Optional,
        public readonly string|Optional $group = new Optional,
        public readonly string|Optional $priority = new Optional,
        public readonly string|Optional $customFields = new Optional,
        public readonly string|Optional $labels = new Optional,
        public readonly array|Optional $rules = new Optional,
    ) {}

    /**
     * Serialization to meet the requirements of the external Omnidesk API
     *
     * @return array<mixed>
     */
    public function toQuery(): array
    {
        $query = [];
        $data = $this->toArray();

        unset($data['case_id']);

        foreach ($data as $key => $datum) {
            if ($key === 'rules') {
                /** @var array<int> $datum */
                $this->serializeList($query, $key, $datum);
            } else {
                $query[$key] = $datum;
            }
        }

        return $query;
    }
}
