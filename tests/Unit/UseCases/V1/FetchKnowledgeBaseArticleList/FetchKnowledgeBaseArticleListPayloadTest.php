<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchKnowledgeBaseArticleList;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticleList\Payload as FetchKnowledgeBaseArticleListPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\LaravelData\Optional;

final class FetchKnowledgeBaseArticleListPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'page' => 2,
                'limit' => 50,
                'search' => 'test query',
                'section_id' => '10',
                'language_id' => '1',
                'sort' => 'id_desc',
            ],

            'expected' => new FetchKnowledgeBaseArticleListPayload(
                page: 2,
                limit: 50,
                search: 'test query',
                sectionId: '10',
                languageId: '1',
                sort: 'id_desc',
            ),
        ];

        yield 'minimal data' => [
            'data' => [],

            'expected' => new FetchKnowledgeBaseArticleListPayload,
        ];

        yield 'with language all' => [
            'data' => [
                'language_id' => 'all',
            ],

            'expected' => new FetchKnowledgeBaseArticleListPayload(
                languageId: 'all',
            ),
        ];

        yield 'with pagination only' => [
            'data' => [
                'page' => 3,
                'limit' => 25,
            ],

            'expected' => new FetchKnowledgeBaseArticleListPayload(
                page: 3,
                limit: 25,
            ),
        ];

        yield 'with search and section filter' => [
            'data' => [
                'search' => 'search term',
                'section_id' => '15',
            ],

            'expected' => new FetchKnowledgeBaseArticleListPayload(
                search: 'search term',
                sectionId: '15',
            ),
        ];

        yield 'with manual order sort' => [
            'data' => [
                'section_id' => '10',
                'sort' => 'manual_order',
            ],

            'expected' => new FetchKnowledgeBaseArticleListPayload(
                sectionId: '10',
                sort: 'manual_order',
            ),
        ];
    }

    public static function querySerializationProvider(): iterable
    {
        yield 'minimal payload' => [
            [],
            [],
        ];

        yield 'page and limit' => [
            [
                'page' => 2,
                'limit' => 50,
            ],
            [
                'page' => 2,
                'limit' => 50,
            ],
        ];

        yield 'search only' => [
            [
                'search' => 'test query',
            ],
            [
                'search' => 'test query',
            ],
        ];

        yield 'section_id only' => [
            [
                'section_id' => '10',
            ],
            [
                'section_id' => '10',
            ],
        ];

        yield 'language_id as string' => [
            [
                'language_id' => '1',
            ],
            [
                'language_id' => '1',
            ],
        ];

        yield 'language_id as all' => [
            [
                'language_id' => 'all',
            ],
            [
                'language_id' => 'all',
            ],
        ];

        yield 'sort as id_desc' => [
            [
                'sort' => 'id_desc',
            ],
            [
                'sort' => 'id_desc',
            ],
        ];

        yield 'sort as created_at_asc' => [
            [
                'sort' => 'created_at_asc',
            ],
            [
                'sort' => 'created_at_asc',
            ],
        ];

        yield 'all parameters' => [
            [
                'page' => 3,
                'limit' => 25,
                'search' => 'test',
                'section_id' => '10',
                'language_id' => '2',
                'sort' => 'manual_order',
            ],
            [
                'page' => 3,
                'limit' => 25,
                'search' => 'test',
                'section_id' => '10',
                'language_id' => '2',
                'sort' => 'manual_order',
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchKnowledgeBaseArticleListPayload $expected): void
    {
        $actual = FetchKnowledgeBaseArticleListPayload::from($data);
        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('querySerializationProvider')]
    public function testToQuery(array $payloadArgs, array $expected): void
    {
        $payload = new FetchKnowledgeBaseArticleListPayload(
            page: $payloadArgs['page'] ?? new Optional,
            limit: $payloadArgs['limit'] ?? new Optional,
            search: $payloadArgs['search'] ?? new Optional,
            sectionId: $payloadArgs['section_id'] ?? new Optional,
            languageId: $payloadArgs['language_id'] ?? new Optional,
            sort: $payloadArgs['sort'] ?? new Optional,
        );

        $this->assertEquals($expected, $payload->toQuery());
    }
}
