<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseCategory\Payload as MoveDownKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseCategory\Response as MoveDownKnowledgeBaseCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class MoveDownKnowledgeBaseCategoryTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'move down knowledge base category' => [
            'categoryId' => 234,
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
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $categoryId, array $response): void
    {
        $url = $this->host."/api/kb_category/$categoryId/movedown.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $payload = new MoveDownKnowledgeBaseCategoryPayload($categoryId);
        $responseData = $this->makeHttpClient()->knowledgeBase()->moveDownCategory($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(MoveDownKnowledgeBaseCategoryResponse::from($response), $responseData);
    }
}
