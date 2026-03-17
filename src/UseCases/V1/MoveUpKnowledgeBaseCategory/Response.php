<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseCategory;

use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class Response extends Data
{
    public function __construct(
        public readonly KnowledgeBaseCategoryData $kbCategory
    ) {}
}
