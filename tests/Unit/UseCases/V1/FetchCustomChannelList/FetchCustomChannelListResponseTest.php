<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCustomChannelList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\CustomChannelData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCustomChannelList\Response as FetchCustomChannelListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchCustomChannelListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with multiple custom channels' => [
            'data' => [
                'custom_channels' => [
                    [
                        'channel_id' => 1,
                        'channel_api_key' => 'cch1',
                        'title' => 'Custom Channel',
                        'channel_type' => 'async',
                        'icon' => 'fa-question-circle',
                        'webhook_url' => 'http://example.ru/wh/omni_wh/',
                        'active' => true,
                    ],
                    [
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

            'expected' => new FetchCustomChannelListResponse(
                customChannels: new Collection([
                    new CustomChannelData(
                        channelId: 1,
                        channelApiKey: 'cch1',
                        title: 'Custom Channel',
                        channelType: 'async',
                        icon: 'fa-question-circle',
                        webhookUrl: 'http://example.ru/wh/omni_wh/',
                        active: true,
                    ),
                    new CustomChannelData(
                        channelId: 3,
                        channelApiKey: 'cch3',
                        title: 'Custom Channel Chat',
                        channelType: 'sync',
                        icon: 'fa-bullhorn',
                        webhookUrl: 'http://example.ru/wh/omni_wh_chat/',
                        active: true,
                    ),
                ]),
                totalCount: 2
            ),
        ];

        yield 'empty custom channels list' => [
            'data' => [
                'custom_channels' => [],
                'total_count' => 0,
            ],

            'expected' => new FetchCustomChannelListResponse(
                customChannels: new Collection([]),
                totalCount: 0
            ),
        ];

        yield 'single custom channel' => [
            'data' => [
                'custom_channels' => [
                    [
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

            'expected' => new FetchCustomChannelListResponse(
                customChannels: new Collection([
                    new CustomChannelData(
                        channelId: 1,
                        channelApiKey: 'cch1',
                        title: 'Single Channel',
                        channelType: 'async',
                        icon: 'fa-question-circle',
                        webhookUrl: 'http://example.ru/wh/omni_wh/',
                        active: true,
                    ),
                ]),
                totalCount: 1
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCustomChannelListResponse $expected): void
    {
        $actual = FetchCustomChannelListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
