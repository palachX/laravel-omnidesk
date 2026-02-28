<?php

declare(strict_types=1);

namespace Palach\Omnidesk;

use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Clients\FiltersClient;
use Palach\Omnidesk\Clients\LabelsClient;
use Palach\Omnidesk\Clients\MessagesClient;
use Palach\Omnidesk\Clients\NotesClient;

final readonly class Omnidesk
{
    public function __construct(
        private CasesClient $cases,
        private FiltersClient $filters,
        private LabelsClient $labels,
        private MessagesClient $messages,
        private NotesClient $notes,
    ) {}

    public function cases(): CasesClient
    {
        return $this->cases;
    }

    public function filters(): FiltersClient
    {
        return $this->filters;
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
}
