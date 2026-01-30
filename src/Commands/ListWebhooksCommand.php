<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Commands;

use Illuminate\Console\Command;
use Palach\Omnidesk\Models\OmniWebhook;

class ListWebhooksCommand extends Command
{
    protected $signature = 'omnidesk:webhooks:list';

    protected $description = 'View list webhooks';

    public function handle(): int
    {
        $webhooks = OmniWebhook::query()->get([
            'id',
            'name',
            'channel',
            'created_at',
        ]);

        if ($webhooks->isEmpty()) {
            $this->info('No webhooks found');

            return self::SUCCESS;
        }

        $this->table(
            ['ID', 'Name', 'Channel', 'Created at'],
            $webhooks->map(fn ($w) => [
                $w->id,
                $w->name,
                $w->channel,
                $w->created_at,
            ])->toArray()
        );

        return self::SUCCESS;

    }
}
