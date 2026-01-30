<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests;

use Faker\Factory;
use Faker\Generator;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;
use Palach\Omnidesk\Services\HttpClient;

abstract class AbstractTestCase extends TestCase
{
    use WithWorkbench;

    protected string $host = 'http://localhost.omnidesk.ru';

    protected string $email = 'example@example.ru';

    protected string $apiKey = 'api_key';

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set(
            'app.key',
            'base64:'.base64_encode(random_bytes(32))
        );

        $app['config']->set('omnidesk', [
            'host' => $this->host,
            'email' => $this->email,
            'api_key' => $this->apiKey,
            'webhooks' => [],
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function arraysDiffRecursive(array $expected, array $actual, string $path = ''): array
    {
        $diff = [];

        foreach ($expected as $key => $expectedValue) {
            $currentPath = $path === '' ? $key : $path.'.'.$key;

            if (! array_key_exists($key, $actual)) {
                $diff[] = "Missing key: $currentPath";

                continue;
            }

            if (is_array($expectedValue) && is_array($actual[$key])) {
                $nestedDiff = $this->arraysDiffRecursive($expectedValue, $actual[$key], $currentPath);
                $diff = array_merge($diff, $nestedDiff);
            } else {
                if ($expectedValue !== $actual[$key]) {
                    $diff[] = "Different values by key: $currentPath (expected: "
                        .var_export($expectedValue, true).', actual: '.var_export($actual[$key], true).')';
                }
            }
        }

        return $diff;
    }

    protected function makeHttpClient(): HttpClient
    {
        return $this->app->make(HttpClient::class);
    }

    protected function faker(): Generator
    {
        static $faker;

        if (! $faker) {
            $faker = Factory::create();
        }

        return $faker;
    }
}
