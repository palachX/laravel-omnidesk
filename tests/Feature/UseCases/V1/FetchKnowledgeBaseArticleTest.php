<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\KnowledgeBaseArticleData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticle\Payload as FetchKnowledgeBaseArticlePayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticle\Response as FetchKnowledgeBaseArticleResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchKnowledgeBaseArticleTest extends AbstractTestCase
{
    private const string API_URL_ARTICLE = '/api/kb_article/%d.json';

    public static function dataProvider(): iterable
    {
        yield 'full article data with single language' => [
            'payload' => [
                'article_id' => 100,
                'language_id' => '1',
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

        yield 'full article data with multiple languages' => [
            'payload' => [
                'article_id' => 200,
                'language_id' => 'all',
            ],
            'response' => [
                'kb_article' => [
                    'article_id' => 200,
                    'section_id' => 20,
                    'section_id_arr' => [20, 21],
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
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];

        yield 'article with all languages parameter' => [
            'payload' => [
                'article_id' => 300,
                'language_id' => 'all',
            ],
            'response' => [
                'kb_article' => [
                    'article_id' => 300,
                    'section_id' => 30,
                    'section_id_arr' => [30, 31, 32],
                    'article_title' => [
                        '1' => 'Тестовая статья',
                        '2' => 'Test article',
                        '3' => 'Articolo di prova',
                    ],
                    'article_content' => [
                        '1' => 'Тестовое содержание статьи',
                        '2' => 'Test article content',
                        '3' => 'Contenuto articolo di prova',
                    ],
                    'article_tags' => [
                        '1' => 'тест,тег,статья',
                        '2' => 'test,tag,article',
                        '3' => 'prova,tag,articolo',
                    ],
                    'access_type' => 'private',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];

        yield 'minimal article data' => [
            'payload' => [
                'article_id' => 400,
            ],
            'response' => [
                'kb_article' => [
                    'article_id' => 400,
                    'section_id' => 40,
                    'article_title' => 'Simple article',
                    'article_content' => 'Simple article content',
                    'access_type' => 'public',
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
        $payload = FetchKnowledgeBaseArticlePayload::validateAndCreate($payload);
        $url = sprintf(self::API_URL_ARTICLE, $payload->articleId);

        $query = http_build_query($payload->toQuery(), '', '&', PHP_QUERY_RFC3986);
        $fullUrl = $this->host.$url;

        if ($query !== '') {
            $fullUrl .= '?'.$query;
        }

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $article = $this->makeHttpClient()->knowledgeBase()->fetchArticle($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $this->assertEquals(new FetchKnowledgeBaseArticleResponse(
            kbArticle: KnowledgeBaseArticleData::from($response['kb_article'])
        ), $article);
    }
}
