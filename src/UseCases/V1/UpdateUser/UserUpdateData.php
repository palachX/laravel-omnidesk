<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\UpdateUser;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class UserUpdateData extends Data
{
    /**
     * @param  array<string, string|int|bool>|Optional  $customFields
     */
    public function __construct(
        public readonly string|Optional $userEmail = new Optional,
        public readonly string|Optional $userPhone = new Optional,
        public readonly string|Optional $userFullName = new Optional,
        public readonly string|Optional $companyName = new Optional,
        public readonly string|Optional $companyPosition = new Optional,
        public readonly string|Optional $userNote = new Optional,
        public readonly int|Optional $languageId = new Optional,
        public readonly array|Optional $customFields = new Optional
    ) {}
}
