<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Payload as FetchKnowledgeBaseCategoryListPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Response as FetchKnowledgeBaseCategoryListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchKnowledgeBaseCategoryListTest extends AbstractTestCase
{
    private const string API_URL_KB_CATEGORY = '/api/kb_category.json';

    public static function dataProvider(): iterable
    {
        yield 'full data with single language' => [
            'payload' => [
                'page' => 1,
                'limit' => 10,
                'language_id' => '1',
            ],
            'response' => [
                [
                    'kb_category' => [
                        'category_id' => 234,
                        'category_title' => 'Test category 1',
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                [
                    'kb_category' => [
                        'category_id' => 235,
                        'category_title' => 'Test category 2',
                        'active' => false,
                        'created_at' => 'Mon, 15 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 13 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total_count' => 10,
            ],
        ];

        yield 'full data with all languages' => [
            'payload' => [
                'page' => 1,
                'limit' => 10,
                'language_id' => 'all',
            ],
            'response' => [
                [
                    'kb_category' => [
                        'category_id' => 234,
                        'category_title' => [
                            '1' => 'Тест 1',
                            '2' => 'Test category 1',
                        ],
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                [
                    'kb_category' => [
                        'category_id' => 235,
                        'category_title' => [
                            '1' => 'Тест 2',
                            '2' => 'Test category 2',
                        ],
                        'active' => false,
                        'created_at' => 'Mon, 15 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 13 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total_count' => 10,
            ],
        ];

        yield 'not full data' => [
            'payload' => [
                'language_id' => '1',
            ],
            'response' => [
                [
                    'kb_category' => [
                        'category_id' => 234,
                        'category_title' => 'Test category 1',
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchKnowledgeBaseCategoryListPayload::from($payload);

        $url = self::API_URL_KB_CATEGORY;
        $query = http_build_query($payload->toQuery());
        $fullUrl = $this->host.$url.'?'.$query;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->knowledgeBase()->fetchList(FetchKnowledgeBaseCategoryListPayload::from($payload));

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{kb_category: array<string, mixed>}> $categoriesRaw
         */
        $categoriesRaw = array_values($response);

        $categories = collect($categoriesRaw)
            ->map(function (array $item) {
                return KnowledgeBaseCategoryData::from($item['kb_category']);
            });

        $this->assertEquals(new FetchKnowledgeBaseCategoryListResponse(
            kbCategories: $categories,
            total: $total
        ), $list);
    }
}
