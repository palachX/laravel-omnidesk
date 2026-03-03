<?php

declare(strict_types=1);

namespace Palach\Omnidesk\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class UserData extends Data
{
    /**
     * @param  array<string, string|int|bool>|Optional  $customFields
     * @param  int[]|Optional  $linkedUsers
     */
    public function __construct(
        public readonly int $userId,
        public readonly string|Optional $userFullName = new Optional,
        public readonly string|Optional $companyName = new Optional,
        public readonly string|Optional $companyPosition = new Optional,
        public readonly string|Optional $thumbnail = new Optional,
        public readonly bool|Optional $confirmed = new Optional,
        public readonly bool|Optional $active = new Optional,
        public readonly bool|Optional $deleted = new Optional,
        public readonly string|Optional $createdAt = new Optional,
        public readonly string|Optional $updatedAt = new Optional,
        public readonly string|Optional $password = new Optional,
        public readonly string|Optional $type = new Optional,
        public readonly string|Optional $userEmail = new Optional,
        public readonly string|Optional $userPhone = new Optional,
        public readonly string|Optional $userWhatsappPhone = new Optional,
        public readonly string|Optional $userVkontakte = new Optional,
        public readonly string|Optional $userOdnoklassniki = new Optional,
        public readonly string|Optional $userFacebook = new Optional,
        public readonly string|Optional $userInstagram = new Optional,
        public readonly string|Optional $telegramId = new Optional,
        public readonly string|Optional $userTelegramData = new Optional,
        public readonly string|Optional $userViber = new Optional,
        public readonly string|Optional $userSkype = new Optional,
        public readonly string|Optional $userLine = new Optional,
        public readonly string|Optional $userSlack = new Optional,
        public readonly string|Optional $userMattermost = new Optional,
        public readonly string|Optional $userAvito = new Optional,
        public readonly string|Optional $userCustomId = new Optional,
        public readonly string|Optional $userCustomChannel = new Optional,
        public readonly string|Optional $userNote = new Optional,
        public readonly int|Optional $languageId = new Optional,
        public readonly array|Optional $customFields = new Optional,
        public readonly array|Optional $linkedUsers = new Optional
    ) {}
}
