<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\Payload as UpdateKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\Response as UpdateKnowledgeBaseCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UpdateKnowledgeBaseCategoryTest extends AbstractTestCase
{
    private const string API_URL_KB_CATEGORY = '/api/kb_category.json';

    public static function dataArrayProvider(): iterable
    {
        yield [
            'categoryId' => 234,
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
            'categoryId' => 235,
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
            'categoryId' => 236,
            'payload' => [
                'kb_category' => [
                    'category_title' => 'Updated Category Name',
                ],
            ],
            'response' => [
                'kb_category' => [
                    'category_id' => 236,
                    'category_title' => 'Updated Category Name',
                    'active' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $categoryId, array $payload, array $response): void
    {
        $url = $this->host.str_replace('.json', "/{$categoryId}.json", self::API_URL_KB_CATEGORY);

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->knowledgeBase()->updateCategory($categoryId, UpdateKnowledgeBaseCategoryPayload::from($payload));

        $payload = UpdateKnowledgeBaseCategoryPayload::from($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(UpdateKnowledgeBaseCategoryResponse::from($response), $responseData);
    }
}
