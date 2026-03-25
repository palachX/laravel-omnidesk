<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\EnableCategory\Payload as EnableCategoryPayload;
use Palach\Omnidesk\UseCases\V1\EnableIdeaCategory\Response as EnableIdeaCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class EnableIdeaCategoryTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'enable idea category' => [
            'categoryId' => 234,
            'response' => [
                'ideas_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category 2',
                    'category_default_group' => 43983,
                    'active' => true,
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $categoryId, array $response): void
    {
        $url = $this->host."/api/ideas_category/$categoryId/enable.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $payload = new EnableCategoryPayload($categoryId);
        $responseData = $this->makeHttpClient()->ideaCategories()->enable($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(EnableIdeaCategoryResponse::from($response), $responseData);
    }
}
