<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseArticle\Payload as StoreKnowledgeBaseArticlePayload;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseArticle\Response as StoreKnowledgeBaseArticleResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class StoreKnowledgeBaseArticleTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield [
            'payload' => [
                'kb_article' => [
                    'article_title' => 'Test article title',
                    'article_content' => 'Test article content',
                    'article_tags' => 'test,article,content',
                    'section_id' => [10, 11],
                    'access_type' => 'public',
                ],
            ],
            'response' => [
                'kb_article' => [
                    'article_id' => 100,
                    'section_id' => 10,
                    'section_id_arr' => [10, 11],
                    'article_title' => 'Test article title',
                    'article_content' => 'Test article content',
                    'article_tags' => 'tag,tag,tag',
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield [
            'payload' => [
                'kb_article' => [
                    'article_title' => [
                        '1' => 'Название статьи',
                        '2' => 'Test article title',
                    ],
                    'article_content' => [
                        '1' => 'Содержание статьи',
                        '2' => 'Test article content',
                    ],
                    'article_tags' => [
                        '1' => 'тег,тег,тег',
                        '2' => 'tag,tag,tag',
                    ],
                    'section_id' => [10, 11],
                    'access_type' => 'staff_only',
                ],
            ],
            'response' => [
                'kb_article' => [
                    'article_id' => 101,
                    'section_id' => 10,
                    'section_id_arr' => [10, 11],
                    'article_title' => [
                        '1' => 'Название статьи',
                        '2' => 'Test article title',
                    ],
                    'article_content' => [
                        '1' => 'Содержание статьи',
                        '2' => 'Test article content',
                    ],
                    'article_tags' => [
                        '1' => 'тег,тег,тег',
                        '2' => 'tag,tag,tag',
                    ],
                    'access_type' => 'staff_only',
                    'active' => false,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Thu, 25 Dec 2014 15:30:00 +0200',
                ],
            ],
        ];
        yield [
            'payload' => [
                'kb_article' => [
                    'article_title' => 'Minimal Article',
                    'article_content' => 'Minimal content',
                    'section_id' => '15',
                ],
            ],
            'response' => [
                'kb_article' => [
                    'article_id' => 102,
                    'section_id' => 15,
                    'article_title' => 'Minimal Article',
                    'article_content' => 'Minimal content',
                    'article_tags' => '',
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],
        ];
        yield [
            'payload' => [
                'kb_article' => [
                    'article_title' => 'Single Section Article',
                    'article_content' => 'Article with single section',
                    'article_tags' => ['single', 'section'],
                    'section_id' => '20',
                    'access_type' => 'public',
                ],
            ],
            'response' => [
                'kb_article' => [
                    'article_id' => 103,
                    'section_id' => 20,
                    'article_title' => 'Single Section Article',
                    'article_content' => 'Article with single section',
                    'article_tags' => ['single', 'section'],
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Fri, 25 Aug 2023 16:45:00 +0300',
                    'updated_at' => 'Sat, 27 Dec 2014 18:50:00 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = StoreKnowledgeBaseArticlePayload::validateAndCreate($payload);
        $url = $this->host.'/api/kb_article.json';

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->knowledgeBase()->storeArticle($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(StoreKnowledgeBaseArticleResponse::from($response), $responseData);
    }
}
