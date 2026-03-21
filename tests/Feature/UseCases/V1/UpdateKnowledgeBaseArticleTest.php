<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseArticle\Payload as UpdateKnowledgeBaseArticlePayload;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseArticle\Response as UpdateKnowledgeBaseArticleResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UpdateKnowledgeBaseArticleTest extends AbstractTestCase
{
    private const string API_URL_KB_ARTICLE = '/api/kb_article.json';

    public static function dataArrayProvider(): iterable
    {
        yield [
            'articleId' => 100,
            'payload' => [
                'kb_article' => [
                    'article_title' => 'Test article title 2',
                    'article_content' => 'Test article content 2',
                    'article_tags' => 'tag,tag_new',
                    'section_id' => 20,
                ],
            ],
            'response' => [
                'kb_article' => [
                    'article_id' => 100,
                    'section_id' => 20,
                    'article_title' => 'Test article title 2',
                    'article_content' => 'Test article content 2',
                    'article_tags' => 'tag,tag_new',
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield [
            'articleId' => 101,
            'payload' => [
                'kb_article' => [
                    'article_title' => [
                        '1' => 'Название статьи 2',
                        '2' => 'Test article title 2',
                    ],
                    'article_content' => [
                        '1' => 'Содержание статьи 2',
                        '2' => 'Test article content 2',
                    ],
                    'article_tags' => [
                        '1' => 'тег,тег',
                        '2' => 'tag,tag',
                    ],
                    'section_id' => 20,
                ],
            ],
            'response' => [
                'kb_article' => [
                    'article_id' => 101,
                    'section_id' => 20,
                    'article_title' => [
                        '1' => 'Название статьи 2',
                        '2' => 'Test article title 2',
                    ],
                    'article_content' => [
                        '1' => 'Содержание статьи 2',
                        '2' => 'Test article content 2',
                    ],
                    'article_tags' => [
                        '1' => 'тег,тег',
                        '2' => 'tag,tag',
                    ],
                    'access_type' => 'public',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield [
            'articleId' => 102,
            'payload' => [
                'kb_article' => [
                    'article_title' => 'Updated Article Title',
                    'article_content' => 'Updated article content',
                    'article_tags' => 'updated,tag',
                    'section_id' => 25,
                    'access_type' => 'staff_only',
                ],
            ],
            'response' => [
                'kb_article' => [
                    'article_id' => 102,
                    'section_id' => 25,
                    'article_title' => 'Updated Article Title',
                    'article_content' => 'Updated article content',
                    'article_tags' => 'updated,tag',
                    'access_type' => 'staff_only',
                    'active' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $articleId, array $payload, array $response): void
    {
        $url = $this->host.str_replace('.json', "/{$articleId}.json", self::API_URL_KB_ARTICLE);

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->knowledgeBase()->updateArticle($articleId, UpdateKnowledgeBaseArticlePayload::from($payload));

        $payload = UpdateKnowledgeBaseArticlePayload::from($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(UpdateKnowledgeBaseArticleResponse::from($response), $responseData);
    }
}
