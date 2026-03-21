<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\KnowledgeBaseArticleData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticleList\Payload as FetchKnowledgeBaseArticleListPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticleList\Response as FetchKnowledgeBaseArticleListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchKnowledgeBaseArticleListTest extends AbstractTestCase
{
    private const string API_URL_KB_ARTICLES = '/api/kb_article.json';

    public static function dataProvider(): iterable
    {
        yield 'full data with multiple articles' => [
            'response' => [
                '0' => [
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
                '1' => [
                    'kb_article' => [
                        'article_id' => 101,
                        'section_id' => 11,
                        'section_id_arr' => [11],
                        'article_title' => 'Test article title 2',
                        'article_content' => 'Test article content 2',
                        'article_tags' => 'tag2,tag2,tag2',
                        'access_type' => 'public',
                        'active' => false,
                        'created_at' => 'Mon, 15 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 13 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total_count' => 10,
            ],
            'payload' => [
                'page' => 1,
                'limit' => 100,
            ],
        ];

        yield 'multilingual articles' => [
            'response' => [
                '0' => [
                    'kb_article' => [
                        'article_id' => 100,
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
                        'access_type' => 'public',
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                '1' => [
                    'kb_article' => [
                        'article_id' => 101,
                        'section_id' => 11,
                        'section_id_arr' => [11],
                        'article_title' => [
                            '1' => 'Название статьи 2',
                            '2' => 'Test article title 2',
                        ],
                        'article_content' => [
                            '1' => 'Содержание статьи 2',
                            '2' => 'Test article content 2',
                        ],
                        'article_tags' => [
                            '1' => 'тег,тег,тег',
                            '2' => 'tag,tag,tag',
                        ],
                        'access_type' => 'public',
                        'active' => false,
                        'created_at' => 'Mon, 15 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 13 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total_count' => 10,
            ],
            'payload' => [
                'language_id' => 'all',
            ],
        ];

        yield 'articles with search and section filter' => [
            'response' => [
                '0' => [
                    'kb_article' => [
                        'article_id' => 100,
                        'section_id' => 10,
                        'section_id_arr' => [10],
                        'article_title' => 'Search result article',
                        'article_content' => 'Content matching search query',
                        'article_tags' => 'search,tag',
                        'access_type' => 'public',
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total_count' => 1,
            ],
            'payload' => [
                'search' => 'search query',
                'section_id' => '10',
                'sort' => 'id_desc',
            ],
        ];

        yield 'single article' => [
            'response' => [
                '0' => [
                    'kb_article' => [
                        'article_id' => 1,
                        'section_id' => 1,
                        'section_id_arr' => [1],
                        'article_title' => 'Single article',
                        'article_content' => 'Single article content',
                        'article_tags' => 'single',
                        'access_type' => 'public',
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total_count' => 1,
            ],
            'payload' => [],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $response, array $payload): void
    {
        $payload = FetchKnowledgeBaseArticleListPayload::from($payload);

        $url = self::API_URL_KB_ARTICLES;
        $query = http_build_query($payload->toQuery(), '', '&', PHP_QUERY_RFC3986);
        $fullUrl = $this->host.$url;

        if ($query !== '') {
            $fullUrl .= '?'.$query;
        }

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->knowledgeBase()->fetchArticleList($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $totalCount = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{kb_article: array<string, mixed>}> $articlesRaw
         */
        $articlesRaw = array_values($response);

        $articles = collect($articlesRaw)
            ->map(function (array $item) {
                return KnowledgeBaseArticleData::from($item['kb_article']);
            });

        $this->assertEquals(new FetchKnowledgeBaseArticleListResponse(
            kbArticles: $articles,
            total: $totalCount
        ), $list);
    }
}
