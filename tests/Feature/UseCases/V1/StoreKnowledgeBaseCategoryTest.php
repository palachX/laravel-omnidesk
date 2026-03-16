<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Payload as StoreKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Response as StoreKnowledgeBaseCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class StoreKnowledgeBaseCategoryTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'store kb category with single language title' => [
            'payload' => new StoreKnowledgeBaseCategoryPayload(
                categoryTitle: 'Test category'
            ),
            'response' => [
                'kb_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];

        yield 'store kb category with multilingual title' => [
            'payload' => new StoreKnowledgeBaseCategoryPayload(
                categoryTitle: [
                    '1' => 'Название категории',
                    '2' => 'Category name',
                ]
            ),
            'response' => [
                'kb_category' => [
                    'category_id' => 235,
                    'category_title' => 'Category name',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(StoreKnowledgeBaseCategoryPayload $payload, array $response): void
    {
        $url = $this->host.'/api/kb_category.json';

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->knowledgeBase()->storeCategory($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode(['kb_category' => $payload->toArray()]);
        });

        $this->assertEquals(StoreKnowledgeBaseCategoryResponse::from($response), $responseData);
    }
}
