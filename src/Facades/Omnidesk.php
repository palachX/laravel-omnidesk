<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Facades;

use Illuminate\Support\Facades\Facade;
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Clients\FiltersClient;
use Palach\Omnidesk\Clients\LabelsClient;
use Palach\Omnidesk\Clients\MessagesClient;
use Palach\Omnidesk\Clients\NotesClient;

/**
 * @method static CasesClient cases()
 * @method static LabelsClient labels()
 * @method static FiltersClient filters()
 * @method static MessagesClient messages()
 * @method static NotesClient notes()
 */
final class Omnidesk extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'omnidesk';
    }
}
