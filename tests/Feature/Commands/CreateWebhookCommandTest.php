<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\Commands;

use Illuminate\Support\Str;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Symfony\Component\Console\Command\Command;

final class CreateWebhookCommandTest extends AbstractTestCase
{
    public function testSuccess(): void
    {
        $this->artisan('omnidesk:webhooks:create')
            ->expectsOutput(__('omnidesk::commands.new_webhook.starting_message'))
            ->expectsQuestion(__('omnidesk::commands.new_webhook.enter_name'), 'Webhook')
            ->expectsQuestion(__('omnidesk::commands.new_webhook.enter_channel'), 'ch102')
            ->expectsConfirmation(__('omnidesk::commands.new_webhook.ask_to_create_api_key'))
            ->expectsOutput(__('omnidesk::commands.new_webhook.webhook_created', [
                'name' => 'Webhook',
            ]))
            ->assertExitCode(Command::SUCCESS);

        $this->assertDatabaseHas('omni_webhooks', [
            'name' => 'Webhook',
            'channel' => 'ch102',
            'api_key' => null,
        ]);
    }

    public function testSuccessWithApiKey(): void
    {
        Str::createRandomStringsUsing(function () {
            return 'fixed-api-key-123';
        });

        $this->artisan('omnidesk:webhooks:create')
            ->expectsOutput(__('omnidesk::commands.new_webhook.starting_message'))
            ->expectsQuestion(__('omnidesk::commands.new_webhook.enter_name'), 'Webhook')
            ->expectsQuestion(__('omnidesk::commands.new_webhook.enter_channel'), 'ch102')
            ->expectsConfirmation(__('omnidesk::commands.new_webhook.ask_to_create_api_key'), 'yes')
            ->expectsOutput(__('omnidesk::commands.new_webhook.webhook_created', [
                'name' => 'Webhook',
            ]))
            ->expectsOutput('Api Key - fixed-api-key-123')
            ->assertExitCode(Command::SUCCESS);

        $this->assertDatabaseHas('omni_webhooks', [
            'name' => 'Webhook',
            'channel' => 'ch102',
        ]);

        $this->assertDatabaseMissing('omni_webhooks', [
            'name' => 'Webhook',
            'channel' => 'ch102',
            'api_key' => null,
        ]);
    }

    public function testError(): void
    {
        $this->artisan('omnidesk:webhooks:create')
            ->expectsOutput(__('omnidesk::commands.new_webhook.starting_message'))
            ->expectsQuestion(__('omnidesk::commands.new_webhook.enter_name'), 'Webhook')
            ->expectsQuestion(__('omnidesk::commands.new_webhook.enter_channel'), null)
            ->expectsConfirmation(__('omnidesk::commands.new_webhook.ask_to_create_api_key'))
            ->assertExitCode(Command::FAILURE);

        $this->assertDatabaseMissing('omni_webhooks', [
            'name' => 'Webhook',
            'channel' => 'ch102',
            'api_key' => null,
        ]);
    }
}
