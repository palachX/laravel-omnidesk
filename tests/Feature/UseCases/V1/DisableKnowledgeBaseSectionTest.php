<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableKnowledgeBaseSection\Response as DisabledKnowledgeBaseSectionResponse;
use Palach\Omnidesk\UseCases\V1\DisableSection\Payload as DisableSectionPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DisableKnowledgeBaseSectionTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'disable knowledge base section' => [
            'sectionId' => 10,
            'response' => [
                'kb_section' => [
                    'section_id' => 10,
                    'category_id' => 2,
                    'section_title' => 'Test section 2',
                    'section_description' => 'Test section description 2',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $sectionId, array $response): void
    {
        $url = $this->host."/api/kb_section/$sectionId/disable.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $payload = new DisableSectionPayload($sectionId);
        $responseData = $this->makeHttpClient()->knowledgeBase()->disableSection($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(DisabledKnowledgeBaseSectionResponse::from($response), $responseData);
    }
}
