<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategory\Payload as FetchKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategory\Response as FetchKnowledgeBaseCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchKnowledgeBaseCategoryTest extends AbstractTestCase
{
    private const string API_URL_CATEGORY = '/api/kb_category/%d.json';

    public static function dataProvider(): iterable
    {
        yield 'category with string title' => [
            'payload' => [
                'categoryId' => 234,
            ],
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
        yield 'category with array titles' => [
            'payload' => [
                'categoryId' => 235,
            ],
            'response' => [
                'kb_category' => [
                    'category_id' => 235,
                    'category_title' => [
                        '1' => 'Тест 1',
                        '2' => 'Test category 1',
                    ],
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield 'inactive category' => [
            'payload' => [
                'categoryId' => 236,
            ],
            'response' => [
                'kb_category' => [
                    'category_id' => 236,
                    'category_title' => 'Inactive category',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield 'category with language_id' => [
            'payload' => [
                'categoryId' => 237,
                'languageId' => '2',
            ],
            'response' => [
                'kb_category' => [
                    'category_id' => 237,
                    'category_title' => 'Category with language',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchKnowledgeBaseCategoryPayload::from($payload);

        $url = sprintf(self::API_URL_CATEGORY, $payload->categoryId);
        $query = http_build_query($payload->toQuery());
        $fullUrl = $this->host.$url;

        if ($query !== '') {
            $fullUrl .= '?'.$query;
        }

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $category = $this->makeHttpClient()->knowledgeBase()->fetchCategory($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $this->assertEquals(new FetchKnowledgeBaseCategoryResponse(
            kbCategory: KnowledgeBaseCategoryData::from($response['kb_category'])
        ), $category);
    }
}
