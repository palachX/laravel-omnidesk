<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaCategory\Payload as UpdateIdeaCategoryPayload;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaCategory\Response as UpdateIdeaCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UpdateIdeaCategoryTest extends AbstractTestCase
{
    private const string API_URL_IDEAS_CATEGORY = '/api/ideas_category.json';

    public static function dataArrayProvider(): iterable
    {
        yield [
            'categoryId' => 100,
            'payload' => [
                'ideas_category' => [
                    'category_title' => 'Test category 2',
                    'category_default_group' => 43983,
                ],
            ],
            'response' => [
                'ideas_category' => [
                    'category_id' => 100,
                    'category_title' => 'Test category 2',
                    'active' => true,
                    'category_default_group' => 43983,
                ],
            ],
        ];
        yield [
            'categoryId' => 101,
            'payload' => [
                'ideas_category' => [
                    'category_title' => 'Updated Category Name',
                ],
            ],
            'response' => [
                'ideas_category' => [
                    'category_id' => 101,
                    'category_title' => 'Updated Category Name',
                    'active' => true,
                    'category_default_group' => 43984,
                ],
            ],
        ];
        yield [
            'categoryId' => 102,
            'payload' => [
                'ideas_category' => [
                    'category_title' => 'Category with new group',
                    'category_default_group' => 43985,
                ],
            ],
            'response' => [
                'ideas_category' => [
                    'category_id' => 102,
                    'category_title' => 'Category with new group',
                    'active' => false,
                    'category_default_group' => 43985,
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $categoryId, array $payload, array $response): void
    {
        $url = $this->host.str_replace('.json', "/{$categoryId}.json", self::API_URL_IDEAS_CATEGORY);

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->ideaCategories()->update($categoryId, UpdateIdeaCategoryPayload::from($payload));

        $payload = UpdateIdeaCategoryPayload::from($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(UpdateIdeaCategoryResponse::from($response), $responseData);
    }
}
