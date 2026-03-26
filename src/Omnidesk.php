<?php

declare(strict_types=1);

namespace Palach\Omnidesk;

use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Clients\ClientEmailsClient;
use Palach\Omnidesk\Clients\CompaniesClient;
use Palach\Omnidesk\Clients\CustomChannelsClient;
use Palach\Omnidesk\Clients\CustomFieldsClient;
use Palach\Omnidesk\Clients\FiltersClient;
use Palach\Omnidesk\Clients\GroupsClient;
use Palach\Omnidesk\Clients\IdeaCategoriesClient;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\Clients\LabelsClient;
use Palach\Omnidesk\Clients\LanguagesClient;
use Palach\Omnidesk\Clients\MacrosClient;
use Palach\Omnidesk\Clients\MessagesClient;
use Palach\Omnidesk\Clients\NotesClient;
use Palach\Omnidesk\Clients\StaffsClient;
use Palach\Omnidesk\Clients\StatisticsClient;
use Palach\Omnidesk\Clients\UsersClient;

final readonly class Omnidesk
{
    public function __construct(
        private CasesClient $cases,
        private ClientEmailsClient $clientEmails,
        private CompaniesClient $companies,
        private StaffsClient $staffs,
        private FiltersClient $filters,
        private GroupsClient $groups,
        private LabelsClient $labels,
        private LanguagesClient $languages,
        private MacrosClient $macros,
        private MessagesClient $messages,
        private NotesClient $notes,
        private UsersClient $users,
        private CustomFieldsClient $customFields,
        private CustomChannelsClient $customChannels,
        private KnowledgeBaseClient $knowledgeBase,
        private IdeaCategoriesClient $ideaCategoriesClient,
        private StatisticsClient $statistics,
    ) {}

    public function cases(): CasesClient
    {
        return $this->cases;
    }

    public function clientEmails(): ClientEmailsClient
    {
        return $this->clientEmails;
    }

    public function companies(): CompaniesClient
    {
        return $this->companies;
    }

    public function staffs(): StaffsClient
    {
        return $this->staffs;
    }

    public function statistics(): StatisticsClient
    {
        return $this->statistics;
    }

    public function filters(): FiltersClient
    {
        return $this->filters;
    }

    public function groups(): GroupsClient
    {
        return $this->groups;
    }

    public function labels(): LabelsClient
    {
        return $this->labels;
    }

    public function languages(): LanguagesClient
    {
        return $this->languages;
    }

    public function macros(): MacrosClient
    {
        return $this->macros;
    }

    public function messages(): MessagesClient
    {
        return $this->messages;
    }

    public function notes(): NotesClient
    {
        return $this->notes;
    }

    public function users(): UsersClient
    {
        return $this->users;
    }

    public function customFields(): CustomFieldsClient
    {
        return $this->customFields;
    }

    public function customChannels(): CustomChannelsClient
    {
        return $this->customChannels;
    }

    public function knowledgeBase(): KnowledgeBaseClient
    {
        return $this->knowledgeBase;
    }

    public function ideaCategories(): IdeaCategoriesClient
    {
        return $this->ideaCategoriesClient;
    }
}
