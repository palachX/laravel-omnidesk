<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Facades;

use Illuminate\Support\Facades\Facade;
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Clients\ClientEmailsClient;
use Palach\Omnidesk\Clients\CompaniesClient;
use Palach\Omnidesk\Clients\CustomChannelsClient;
use Palach\Omnidesk\Clients\CustomFieldsClient;
use Palach\Omnidesk\Clients\FiltersClient;
use Palach\Omnidesk\Clients\GroupsClient;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\Clients\LabelsClient;
use Palach\Omnidesk\Clients\LanguagesClient;
use Palach\Omnidesk\Clients\MacrosClient;
use Palach\Omnidesk\Clients\MessagesClient;
use Palach\Omnidesk\Clients\NotesClient;
use Palach\Omnidesk\Clients\StaffsClient;
use Palach\Omnidesk\Clients\UsersClient;

/**
 * @method static CasesClient cases()
 * @method static LabelsClient labels()
 * @method static FiltersClient filters()
 * @method static GroupsClient groups()
 * @method static MacrosClient macros()
 * @method static MessagesClient messages()
 * @method static NotesClient notes()
 * @method static UsersClient users()
 * @method static CompaniesClient companies()
 * @method static StaffsClient staff()
 * @method static CustomFieldsClient customFields()
 * @method static CustomChannelsClient customChannels()
 * @method static ClientEmailsClient clientEmails()
 * @method static LanguagesClient languages()
 * @method static KnowledgeBaseClient knowledgeBase()
 */
final class Omnidesk extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'omnidesk';
    }
}
