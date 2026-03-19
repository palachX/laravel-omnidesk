<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteKnowledgeBaseSection\Response as DeleteKnowledgeBaseSectionResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DeleteKnowledgeBaseSectionTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'delete knowledge base section' => [
            'sectionId' => 300,
            'response' => [
                'kb_section' => [
                    'section_id' => 300,
                    'category_id' => 100,
                    'section_title' => 'Test Section',
                    'section_description' => 'Test Description',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $sectionId, array $response): void
    {
        $url = $this->host."/api/kb_section/$sectionId.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->knowledgeBase()->deleteSection($sectionId);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_DELETE
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(DeleteKnowledgeBaseSectionResponse::from($response), $responseData);
    }
}
