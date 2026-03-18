<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\KnowledgeBaseSectionData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSection\Payload as FetchKnowledgeBaseSectionPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSection\Response as FetchKnowledgeBaseSectionResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchKnowledgeBaseSectionTest extends AbstractTestCase
{
    private const string API_URL_SECTION = '/api/kb_section/%d.json';

    public static function dataProvider(): iterable
    {
        yield 'full section data with single language' => [
            'payload' => [
                'section_id' => 10,
                'language_id' => '1',
            ],
            'response' => [
                'kb_section' => [
                    'section_id' => 10,
                    'category_id' => 1,
                    'section_title' => 'Test section',
                    'section_description' => 'Test section description',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];

        yield 'full section data with multiple languages' => [
            'payload' => [
                'section_id' => 20,
                'language_id' => 'all',
            ],
            'response' => [
                'kb_section' => [
                    'section_id' => 20,
                    'category_id' => 2,
                    'section_title' => [
                        '1' => 'Тест 1',
                        '2' => 'Test section 1',
                    ],
                    'section_description' => [
                        '1' => 'Тест описание 1',
                        '2' => 'Test section description 1',
                    ],
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];

        yield 'section with all languages parameter' => [
            'payload' => [
                'section_id' => 40,
                'language_id' => 'all',
            ],
            'response' => [
                'kb_section' => [
                    'section_id' => 40,
                    'category_id' => 4,
                    'section_title' => [
                        '1' => 'Тест раздел',
                        '2' => 'Test section',
                        '3' => 'Sezione di test',
                    ],
                    'section_description' => [
                        '1' => 'Тест описание раздела',
                        '2' => 'Test section description',
                        '3' => 'Descrizione sezione di test',
                    ],
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];

        yield 'minimal section data' => [
            'payload' => [
                'section_id' => 10,
            ],
            'response' => [
                'kb_section' => [
                    'section_id' => 50,
                    'category_id' => 5,
                    'section_title' => 'Simple section',
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
        $payload = FetchKnowledgeBaseSectionPayload::validateAndCreate($payload);
        $url = sprintf(self::API_URL_SECTION, $payload->sectionId);

        $query = http_build_query($payload->toQuery(), '', '&', PHP_QUERY_RFC3986);
        $fullUrl = $this->host.$url;

        if ($query !== '') {
            $fullUrl .= '?'.$query;
        }

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $section = $this->makeHttpClient()->knowledgeBase()->getSection($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $this->assertEquals(new FetchKnowledgeBaseSectionResponse(
            kbSection: KnowledgeBaseSectionData::from($response['kb_section'])
        ), $section);
    }
}
