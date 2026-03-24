<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\IdeaCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategoryList\Payload as FetchIdeaCategoryPayload;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategoryList\Response as FetchIdeaCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchIdeaCategoryTest extends AbstractTestCase
{
    private const string API_URL_IDEAS_CATEGORY = '/api/ideas_category.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'payload' => [
                'page' => 1,
                'limit' => 10,
            ],
            'response' => [
                [
                    'ideas_category' => [
                        'category_id' => 234,
                        'category_title' => 'Test category 1',
                        'category_default_group' => 44152,
                        'active' => true,
                    ],
                ],
                [
                    'ideas_category' => [
                        'category_id' => 235,
                        'category_title' => 'Test category 2',
                        'category_default_group' => 43983,
                        'active' => false,
                    ],
                ],
                'total_count' => 10,
            ],
        ];
        yield 'not full data' => [
            'payload' => [],
            'response' => [
                [
                    'ideas_category' => [
                        'category_id' => 234,
                        'category_title' => 'Test category 1',
                        'category_default_group' => 44152,
                        'active' => true,
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchIdeaCategoryPayload::from($payload);

        $url = self::API_URL_IDEAS_CATEGORY;
        $query = http_build_query($payload->toQuery());
        $fullUrl = $this->host.$url.(! empty($query) ? '?'.$query : '');

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->ideaCategories()->fetchList(FetchIdeaCategoryPayload::from($payload));

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{ideas_category: array<string, mixed>}> $categoriesRaw
         */
        $categoriesRaw = array_values(array_filter($response, fn ($item) => isset($item['ideas_category'])));

        $categories = collect($categoriesRaw)
            ->map(function (array $item) {
                return IdeaCategoryData::from($item['ideas_category']);
            });

        $this->assertEquals(new FetchIdeaCategoryResponse(
            ideaCategories: $categories,
            total: $total
        ), $list);
    }
}
