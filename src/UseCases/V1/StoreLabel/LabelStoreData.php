<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\StoreLabel;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class LabelStoreData extends Data
{
    public function __construct(
        #[Required]
        public readonly string $labelTitle
    ) {}
}
