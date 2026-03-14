<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchStaff;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data
{
    public function __construct(
        #[Required]
        public readonly int $staffId,
        public readonly int|Optional $languageId = new Optional,
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

        unset($data['staff_id']);

        foreach ($data as $key => $datum) {
            if ($datum instanceof Optional) {
                continue;
            }

            $query[$key] = $datum;
        }

        return $query;
    }
}
