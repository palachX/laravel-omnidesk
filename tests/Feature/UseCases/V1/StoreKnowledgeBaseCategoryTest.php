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
        yield [
            'payload' => [
                'kb_category' => [
                    'category_title' => 'Test category 2',
                ],
            ],
            'response' => [
                'kb_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category 2',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield [
            'payload' => [
                'kb_category' => [
                    'category_title' => [
                        '1' => 'Тест 2',
                        '2' => 'Test category 2',
                    ],
                ],
            ],
            'response' => [
                'kb_category' => [
                    'category_id' => 235,
                    'category_title' => [
                        '1' => 'Тест 2',
                        '2' => 'Test category 2',
                    ],
                    'active' => true,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Thu, 25 Dec 2014 15:30:00 +0200',
                ],
            ],
        ];
        yield [
            'payload' => [
                'kb_category' => [
                    'category_title' => 'Stored Category Name',
                ],
            ],
            'response' => [
                'kb_category' => [
                    'category_id' => 236,
                    'category_title' => 'Stored Category Name',
                    'active' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = StoreKnowledgeBaseCategoryPayload::validateAndCreate($payload);
        $url = $this->host.'/api/kb_category.json';

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->knowledgeBase()->storeCategory($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(StoreKnowledgeBaseCategoryResponse::from($response), $responseData);
    }
}
