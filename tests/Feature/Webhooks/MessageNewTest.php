<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\Webhooks;

use Palach\Omnidesk\Models\OmniWebhook;
use Palach\Omnidesk\Support\Testing\WebhookMessageNew\WebhookMessageNewDataInput;
use Palach\Omnidesk\Support\Testing\WebhookMessageNew\WebhookMessageNewHandler;
use Palach\Omnidesk\Tests\ApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class MessageNewTest extends ApiTestCase
{
    private const string URL_WEBHOOK = '/omnidesk/%s/webhook';

    public static function dataSuccessProvider(): iterable
    {
        yield [
            'data' => [
                'type' => 'message_new',
                'object' => [
                    'message_id' => 42577887,
                    'case_id' => 985687,
                    'staff_id' => 7235,
                    'user_id' => 5654846848,
                    'custom_user_id' => fake()->uuid(),
                    'content' => 'Help is coming!',
                    'note' => 0,
                    'sent_via_rule' => 0,
                ],
            ],
        ];
    }

    public static function dataErrorProvider(): iterable
    {
        yield 'not found case_id' => [
            'data' => [
                'type' => 'message_new',
                'object' => [
                    'message_id' => 42577887,
                    'staff_id' => 7235,
                    'user_id' => 5654846848,
                    'custom_user_id' => fake()->uuid(),
                    'content' => 'Help is coming!',
                    'note' => 0,
                    'sent_via_rule' => 0,
                ],
            ],
        ];
        yield 'not found type' => [
            'data' => [
                'type' => 'message_new_test',
                'object' => [
                    'message_id' => 42577887,
                    'staff_id' => 7235,
                    'case_id' => 985687,
                    'user_id' => 5654846848,
                    'custom_user_id' => fake()->uuid(),
                    'content' => 'Help is coming!',
                    'note' => 0,
                    'sent_via_rule' => 0,
                ],
            ],
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('omnidesk.webhooks', [
            [
                'type' => 'message_new',
                'handler' => WebhookMessageNewHandler::class,
                'data' => WebhookMessageNewDataInput::class,
            ],
        ]);
    }

    #[DataProvider('dataSuccessProvider')]
    public function testSuccess(array $data): void
    {
        $omniWebhook = OmniWebhook::factory()->create();
        $this->postJson(sprintf(self::URL_WEBHOOK, $omniWebhook->id), $data)->assertOk();
    }

    #[DataProvider('dataErrorProvider')]
    public function testError(array $data): void
    {
        $omniWebhook = OmniWebhook::factory()->create();

        $this->postJson(sprintf(self::URL_WEBHOOK, $omniWebhook->id), $data)->assertServerError();
    }

    #[DataProvider('dataSuccessProvider')]
    public function testErrorNotFound(array $data): void
    {
        OmniWebhook::factory()->create();
        $this->postJson(sprintf(self::URL_WEBHOOK, $this->faker()->uuid), $data)->assertNotFound();
    }
}
