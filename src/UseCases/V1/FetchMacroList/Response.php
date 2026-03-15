<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchMacroList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\MacroCategoryData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    /**
     * @param  Collection<int, MacroCategoryData>  $common
     * @param  Collection<int, MacroCategoryData>  $personal
     */
    public function __construct(
        public readonly Collection $common,
        public readonly Collection $personal,
    ) {}
}
