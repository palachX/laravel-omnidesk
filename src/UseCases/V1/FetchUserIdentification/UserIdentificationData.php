<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\FetchUserIdentification;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class UserIdentificationData extends Data
{
    /**
     * @param  string|Optional  $userEmail  Valid email address (required if other contact data not provided)
     * @param  string|Optional  $userPhone  Valid phone number (required if other contact data not provided)
     * @param  string|Optional  $userWhatsappPhone  Valid phone number linked to WhatsApp account (required if other contact data not provided)
     * @param  string|Optional  $userTelegramData  Valid phone or username for Telegram (required if other contact data not provided)
     * @param  string|Optional  $userCustomId  ID for custom channel user (required if other contact data not provided)
     * @param  string|Optional  $userCustomChannel  Custom channel ID (e.g., cch101)
     * @param  string|Optional  $userFullName  User's full name
     * @param  string|Optional  $companyName  User's company name
     * @param  string|Optional  $companyPosition  User's position
     * @param  string|Optional  $userNote  Note about user
     * @param  int|Optional  $languageId  User language ID
     * @param  array<mixed>|Optional  $customFields  Custom fields
     */
    public function __construct(
        #[RequiredWithout(['user_phone', 'user_whatsapp_phone', 'user_telegram_data', 'user_custom_id'])]
        public readonly string|Optional $userEmail = new Optional,

        #[RequiredWithout(['user_email', 'user_whatsapp_phone', 'user_telegram_data', 'user_custom_id'])]
        public readonly string|Optional $userPhone = new Optional,

        #[RequiredWithout(['user_email', 'user_phone', 'user_telegram_data', 'user_custom_id'])]
        public readonly string|Optional $userWhatsappPhone = new Optional,

        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_custom_id'])]
        public readonly string|Optional $userTelegramData = new Optional,

        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_telegram_data'])]
        public readonly string|Optional $userCustomId = new Optional,

        public readonly string|Optional $userCustomChannel = new Optional,
        public readonly string|Optional $userFullName = new Optional,
        public readonly string|Optional $companyName = new Optional,
        public readonly string|Optional $companyPosition = new Optional,
        public readonly string|Optional $userNote = new Optional,
        public readonly int|Optional $languageId = new Optional,
        public readonly array|Optional $customFields = new Optional
    ) {}
}
