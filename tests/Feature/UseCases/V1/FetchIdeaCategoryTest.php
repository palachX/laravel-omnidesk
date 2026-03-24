<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\IdeaCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategory\Payload as FetchIdeaCategoryPayload;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategory\Response as FetchIdeaCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchIdeaCategoryTest extends AbstractTestCase
{
    private const string API_URL_IDEA_CATEGORY = '/api/ideas_category/%d.json';

    public static function dataProvider(): iterable
    {
        yield 'full idea category data' => [
            'categoryId' => 100,
            'response' => [
                'ideas_category' => [
                    'category_id' => 100,
                    'category_title' => 'Feature Requests',
                    'active' => true,
                    'category_default_group' => 5,
                ],
            ],
        ];
        yield 'minimal idea category data' => [
            'categoryId' => 200,
            'response' => [
                'ideas_category' => [
                    'category_id' => 200,
                    'category_title' => 'Bug Reports',
                    'active' => false,
                ],
            ],
        ];
        yield 'inactive category with group' => [
            'categoryId' => 300,
            'response' => [
                'ideas_category' => [
                    'category_id' => 300,
                    'category_title' => 'Improvements',
                    'active' => false,
                    'category_default_group' => 10,
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(int $categoryId, array $response): void
    {
        $payload = new FetchIdeaCategoryPayload($categoryId);

        $url = sprintf(self::API_URL_IDEA_CATEGORY, $categoryId);
        $fullUrl = $this->host.$url;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $ideaCategory = $this->makeHttpClient()->ideaCategories()->getIdeaCategory($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $this->assertEquals(new FetchIdeaCategoryResponse(
            ideasCategory: IdeaCategoryData::from($response['ideas_category'])
        ), $ideaCategory);
    }
}
