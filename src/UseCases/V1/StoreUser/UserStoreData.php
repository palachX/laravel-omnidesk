<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\StoreUser;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class UserStoreData extends Data
{
    /**
     * @param  array<string, string|int|bool>|Optional  $customFields
     */
    public function __construct(
        #[RequiredWithout(['user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_line', 'user_slack', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userEmail = new Optional,
        #[RequiredWithout(['user_email', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_line', 'user_slack', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userPhone = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_line', 'user_slack', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userWhatsappPhone = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_line', 'user_slack', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userVkontakte = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_facebook', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_line', 'user_slack', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userOdnoklassniki = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_line', 'user_slack', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userFacebook = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_line', 'user_slack', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userInstagram = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_line', 'user_slack', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userTelegram = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram', 'user_viber', 'user_skype', 'user_line', 'user_slack', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userTelegramData = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_skype', 'user_line', 'user_slack', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userViber = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_line', 'user_slack', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userSkype = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_slack', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userLine = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_line', 'user_mattermost', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userSlack = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_line', 'user_slack', 'user_avito', 'user_custom_id'])]
        public readonly string|Optional $userMattermost = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_line', 'user_slack', 'user_mattermost', 'user_custom_id'])]
        public readonly string|Optional $userAvito = new Optional,
        #[RequiredWithout(['user_email', 'user_phone', 'user_whatsapp_phone', 'user_vkontakte', 'user_odnoklassniki', 'user_facebook', 'user_instagram', 'user_telegram', 'user_telegram_data', 'user_viber', 'user_skype', 'user_line', 'user_slack', 'user_mattermost', 'user_avito'])]
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
