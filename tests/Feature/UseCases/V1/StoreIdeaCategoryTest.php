<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreIdeaCategory\Payload as StoreIdeaCategoryPayload;
use Palach\Omnidesk\UseCases\V1\StoreIdeaCategory\Response as StoreIdeaCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class StoreIdeaCategoryTest extends AbstractTestCase
{
    private const string API_URL = '/api/ideas_category.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'payload' => [
                'category_title' => 'Test group',
                'category_default_group' => 1,
            ],
            'response' => [
                'ideas_category' => [
                    'category_title' => 'Test group',
                    'category_default_group' => 1,
                    'active' => true,
                    'category_id' => 234,
                ],
            ],
        ];
        yield 'minimal data' => [
            'payload' => [
                'category_title' => 'Test group',
            ],
            'response' => [
                'ideas_category' => [
                    'category_title' => 'Test group',
                    'active' => true,
                    'category_id' => 235,
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = StoreIdeaCategoryPayload::from($payload);
        $fullUrl = $this->host.self::API_URL;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $category = $this->makeHttpClient()->ideaCategories()->store($payload);

        Http::assertSent(function (Request $request) use ($fullUrl, $payload) {
            return $request->url() === $fullUrl
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(StoreIdeaCategoryResponse::from($response), $category);
    }
}
