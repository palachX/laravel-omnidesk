<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Palach\Omnidesk\Models\OmniWebhook;

class CreateWebhookCommand extends Command
{
    protected $signature = 'omnidesk:webhooks:create';

    protected $description = 'Added webhook data';

    public function handle(): int
    {
        $this->info(__('omnidesk::commands.new_webhook.starting_message'));

        /** @var string|null $name */
        $name = $this->ask(__('omnidesk::commands.new_webhook.enter_name'));
        /** @var string $channel */
        $channel = $this->ask(__('omnidesk::commands.new_webhook.enter_channel'));
        $apiKey = null;

        if ($this->confirm(__('omnidesk::commands.new_webhook.ask_to_create_api_key'))) {
            $apiKey = Str::random(30);
        }

        try {
            OmniWebhook::query()->create([
                'name' => $name,
                'channel' => $channel,
                'api_key' => $apiKey,
            ]);

            $this->info(__('omnidesk::commands.new_webhook.webhook_created', [
                'name' => $name ?? '-',
            ]));

            if ($apiKey) {
                $this->info('Api Key - '.$apiKey);
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->info($e->getMessage());

            return self::FAILURE;
        }
    }
}
