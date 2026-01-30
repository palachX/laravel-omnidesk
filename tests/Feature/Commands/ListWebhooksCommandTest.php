<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\Commands;

use Palach\Omnidesk\Models\OmniWebhook;
use Palach\Omnidesk\Tests\AbstractTestCase;

final class ListWebhooksCommandTest extends AbstractTestCase
{
    public function testShowsWebhooksList(): void
    {
        $webhookMain = OmniWebhook::factory()->create([
            'name' => 'Main webhook',
            'channel' => 'ch1',
        ]);

        $webhookBackup = OmniWebhook::factory()->create([
            'name' => 'Backup webhook',
            'channel' => 'ch2',
        ]);

        $this->artisan('omnidesk:webhooks:list')
            ->expectsTable(
                ['ID', 'Name', 'Channel', 'Created at'],
                [
                    [
                        $webhookMain->id,
                        $webhookMain->name,
                        $webhookMain->channel,
                        $webhookMain->created_at,
                    ],
                    [
                        $webhookBackup->id,
                        $webhookBackup->name,
                        $webhookBackup->channel,
                        $webhookBackup->created_at,
                    ],
                ]
            )
            ->assertExitCode(0);
    }

    public function testShowsMessageWhenEmpty(): void
    {
        $this->artisan('omnidesk:webhooks:list')
            ->expectsOutput('No webhooks found')
            ->assertExitCode(0);
    }
}
