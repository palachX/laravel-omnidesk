<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableArticle\Payload as DisableArticlePayload;
use Palach\Omnidesk\UseCases\V1\DisableKnowledgeBaseArticle\Response as DisableKnowledgeBaseArticleResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DisableKnowledgeBaseArticleTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'disable knowledge base article' => [
            'articleId' => 100,
            'response' => [
                'kb_article' => [
                    'article_id' => 100,
                    'section_id' => 20,
                    'article_title' => 'Test article title 2',
                    'article_content' => 'Test article content 2',
                    'access_type' => 'public',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $articleId, array $response): void
    {
        $url = $this->host."/api/kb_article/$articleId/disable.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $payload = new DisableArticlePayload($articleId);
        $responseData = $this->makeHttpClient()->knowledgeBase()->disableArticle($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(DisableKnowledgeBaseArticleResponse::from($response), $responseData);
    }
}
