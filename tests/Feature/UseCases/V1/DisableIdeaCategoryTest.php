<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableIdeaCategory\Payload as DisableIdeaCategoryPayload;
use Palach\Omnidesk\UseCases\V1\DisableIdeaCategory\Response as DisableIdeaCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DisableIdeaCategoryTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'disable idea category' => [
            'categoryId' => 234,
            'response' => [
                'ideas_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category 2',
                    'category_default_group' => 43983,
                    'active' => false,
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $categoryId, array $response): void
    {
        $url = $this->host."/api/ideas_category/$categoryId/disable.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $payload = new DisableIdeaCategoryPayload($categoryId);
        $responseData = $this->makeHttpClient()->ideaCategories()->disable($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(DisableIdeaCategoryResponse::from($response), $responseData);
    }
}
