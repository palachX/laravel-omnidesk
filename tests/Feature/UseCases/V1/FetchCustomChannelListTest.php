<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\CustomChannelData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCustomChannelList\Response as FetchCustomChannelListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchCustomChannelListTest extends AbstractTestCase
{
    private const string API_URL_CUSTOM_CHANNELS = '/api/custom_channels.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'response' => [
                '0' => [
                    'custom_channel' => [
                        'channel_id' => 1,
                        'channel_api_key' => 'cch1',
                        'title' => 'Custom Channel',
                        'channel_type' => 'async',
                        'icon' => 'fa-question-circle',
                        'webhook_url' => 'http://example.ru/wh/omni_wh/',
                        'active' => true,
                    ],
                ],
                '1' => [
                    'custom_channel' => [
                        'channel_id' => 3,
                        'channel_api_key' => 'cch3',
                        'title' => 'Custom Channel Chat',
                        'channel_type' => 'sync',
                        'icon' => 'fa-bullhorn',
                        'webhook_url' => 'http://example.ru/wh/omni_wh_chat/',
                        'active' => true,
                    ],
                ],
                'total_count' => 2,
            ],
        ];
        yield 'no pagination' => [
            'response' => [
                '0' => [
                    'custom_channel' => [
                        'channel_id' => 1,
                        'channel_api_key' => 'cch1',
                        'title' => 'Single Channel',
                        'channel_type' => 'async',
                        'icon' => 'fa-question-circle',
                        'webhook_url' => 'http://example.ru/wh/omni_wh/',
                        'active' => true,
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $response): void
    {
        $url = self::API_URL_CUSTOM_CHANNELS;
        $fullUrl = $this->host.$url;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->customChannels()->fetchList();

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $totalCount = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{custom_channel: array<string, mixed>}> $customChannelsRaw
         */
        $customChannelsRaw = array_values($response);

        $customChannels = collect($customChannelsRaw)
            ->map(function (array $item) {
                return CustomChannelData::from($item['custom_channel']);
            });

        $this->assertEquals(new FetchCustomChannelListResponse(
            customChannels: $customChannels,
            totalCount: $totalCount
        ), $list);
    }
}
