<?php

declare(strict_types=1);

namespace Palach\Omnidesk;

use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Clients\ClientEmailsClient;
use Palach\Omnidesk\Clients\CompaniesClient;
use Palach\Omnidesk\Clients\FiltersClient;
use Palach\Omnidesk\Clients\GroupsClient;
use Palach\Omnidesk\Clients\LabelsClient;
use Palach\Omnidesk\Clients\MacrosClient;
use Palach\Omnidesk\Clients\MessagesClient;
use Palach\Omnidesk\Clients\NotesClient;
use Palach\Omnidesk\Clients\StaffsClient;
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
        private MacrosClient $macros,
        private MessagesClient $messages,
        private NotesClient $notes,
        private UsersClient $users,
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
}
