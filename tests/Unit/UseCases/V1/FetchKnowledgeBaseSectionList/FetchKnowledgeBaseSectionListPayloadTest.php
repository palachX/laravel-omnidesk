<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchKnowledgeBaseSectionList;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSectionList\Payload as FetchKnowledgeBaseSectionListPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\LaravelData\Optional;

final class FetchKnowledgeBaseSectionListPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'page' => 2,
                'limit' => 50,
                'category_id' => '1',
                'language_id' => '1',
            ],

            'expected' => new FetchKnowledgeBaseSectionListPayload(
                categoryId: '1',
                page: 2,
                limit: 50,
                languageId: '1',
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'category_id' => '1',
            ],

            'expected' => new FetchKnowledgeBaseSectionListPayload(
                categoryId: '1',
            ),
        ];

        yield 'with language all' => [
            'data' => [
                'category_id' => '1',
                'language_id' => 'all',
            ],

            'expected' => new FetchKnowledgeBaseSectionListPayload(
                categoryId: '1',
                languageId: 'all',
            ),
        ];

        yield 'with pagination only' => [
            'data' => [
                'category_id' => '1',
                'page' => 3,
                'limit' => 25,
            ],

            'expected' => new FetchKnowledgeBaseSectionListPayload(
                categoryId: '1',
                page: 3,
                limit: 25,
            ),
        ];
    }

    public static function querySerializationProvider(): iterable
    {
        yield 'minimal payload' => [
            [
                'category_id' => '1',
            ],
            [
                'category_id' => '1',
            ],
        ];

        yield 'page and limit' => [
            [
                'category_id' => '1',
                'page' => 2,
                'limit' => 50,
            ],
            [
                'category_id' => '1',
                'page' => 2,
                'limit' => 50,
            ],
        ];

        yield 'language_id as string' => [
            [
                'category_id' => '1',
                'language_id' => '1',
            ],
            [
                'category_id' => '1',
                'language_id' => '1',
            ],
        ];

        yield 'language_id as all' => [
            [
                'category_id' => '1',
                'language_id' => 'all',
            ],
            [
                'category_id' => '1',
                'language_id' => 'all',
            ],
        ];

        yield 'all parameters' => [
            [
                'category_id' => '1',
                'page' => 3,
                'limit' => 25,
                'language_id' => '2',
            ],
            [
                'category_id' => '1',
                'page' => 3,
                'limit' => 25,
                'language_id' => '2',
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchKnowledgeBaseSectionListPayload $expected): void
    {
        $actual = FetchKnowledgeBaseSectionListPayload::from($data);
        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('querySerializationProvider')]
    public function testToQuery(array $payloadArgs, array $expected): void
    {
        $defaultPage = 1;
        $defaultLimit = 100;

        $payload = new FetchKnowledgeBaseSectionListPayload(
            page: $payloadArgs['page'] ?? $defaultPage,
            limit: $payloadArgs['limit'] ?? $defaultLimit,
            categoryId: $payloadArgs['category_id'],
            languageId: $payloadArgs['language_id'] ?? new Optional,
        );

        if (! isset($expected['page'])) {
            $expected['page'] = $defaultPage;
        }

        if (! isset($expected['limit'])) {
            $expected['limit'] = $defaultLimit;
        }

        $this->assertEquals($expected, $payload->toQuery());
    }
}
