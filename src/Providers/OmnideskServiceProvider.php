<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Providers;

use Palach\Omnidesk\Commands\CreateWebhookCommand;
use Palach\Omnidesk\Commands\ListWebhooksCommand;
use Palach\Omnidesk\DTO\OmnideskConfig;
use Palach\Omnidesk\Factories\WebhookHandlerDataInputFactory;
use Palach\Omnidesk\Factories\WebhookHandlerFactory;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OmnideskServiceProvider extends PackageServiceProvider
{
    private function getConfig(): OmnideskConfig
    {
        /** @var array<string, mixed> $configArr */
        $configArr = config('omnidesk');

        return OmnideskConfig::from($configArr);
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('omnidesk')
            ->hasConfigFile()
            ->hasRoute('api')
            ->hasCommands([
                CreateWebhookCommand::class,
                ListWebhooksCommand::class,
            ])
            ->hasMigrations([
                'create_omni_webhooks_table',
            ])
            ->hasTranslations();

        $this->app->singleton(OmnideskTransport::class, fn () => new OmnideskTransport($this->getConfig()));
        $this->app->singleton('omnidesk', Omnidesk::class);

        /**
         * Registration handlers factory
         */
        $this->app->singleton(WebhookHandlerFactory::class, fn () => new WebhookHandlerFactory($this->getConfig()));
        $this->app->singleton(WebhookHandlerDataInputFactory::class, fn () => new WebhookHandlerDataInputFactory($this->getConfig()));
    }
}
