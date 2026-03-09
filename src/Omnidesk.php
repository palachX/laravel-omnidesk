<?php

declare(strict_types=1);

namespace Palach\Omnidesk;

use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Clients\CompaniesClient;
use Palach\Omnidesk\Clients\FiltersClient;
use Palach\Omnidesk\Clients\GroupsClient;
use Palach\Omnidesk\Clients\LabelsClient;
use Palach\Omnidesk\Clients\MessagesClient;
use Palach\Omnidesk\Clients\NotesClient;
use Palach\Omnidesk\Clients\UsersClient;

final readonly class Omnidesk
{
    public function __construct(
        private CasesClient $cases,
        private CompaniesClient $companies,
        private FiltersClient $filters,
        private GroupsClient $groups,
        private LabelsClient $labels,
        private MessagesClient $messages,
        private NotesClient $notes,
        private UsersClient $users,
    ) {}

    public function cases(): CasesClient
    {
        return $this->cases;
    }

    public function companies(): CompaniesClient
    {
        return $this->companies;
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
