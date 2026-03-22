<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteKnowledgeBaseArticle\Payload as DeleteKnowledgeBaseArticlePayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DeleteKnowledgeBaseArticleTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'delete knowledge base article' => [
            'articleId' => 400,
            'response' => [
                'kb_article' => [
                    'article_id' => 400,
                    'section_id' => 200,
                    'article_title' => 'Test Article',
                    'article_text' => 'Test article content',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $articleId, array $response): void
    {
        $url = $this->host."/api/kb_article/$articleId.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $payload = new DeleteKnowledgeBaseArticlePayload($articleId);
        $this->makeHttpClient()->knowledgeBase()->deleteArticle($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_DELETE
                && $request->body() === json_encode([]);
        });
    }
}
