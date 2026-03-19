<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseSection\Payload as UpdateKnowledgeBaseSectionPayload;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseSection\Response as UpdateKnowledgeBaseSectionResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UpdateKnowledgeBaseSectionTest extends AbstractTestCase
{
    private const string API_URL_KB_SECTION = '/api/kb_section.json';

    public static function dataArrayProvider(): iterable
    {
        yield [
            'sectionId' => 10,
            'payload' => [
                'kb_section' => [
                    'section_title' => 'Test section 2',
                    'section_description' => 'Test section description 2',
                    'category_id' => 2,
                ],
            ],
            'response' => [
                'kb_section' => [
                    'section_id' => 10,
                    'category_id' => 2,
                    'section_title' => 'Test section 2',
                    'section_description' => 'Test section description 2',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield [
            'sectionId' => 11,
            'payload' => [
                'kb_section' => [
                    'section_title' => [
                        '1' => 'Тест 2',
                        '2' => 'Test section 2',
                    ],
                    'section_description' => [
                        '1' => 'Тест описание 2',
                        '2' => 'Test section description 2',
                    ],
                    'category_id' => 2,
                ],
            ],
            'response' => [
                'kb_section' => [
                    'section_id' => 11,
                    'category_id' => 2,
                    'section_title' => [
                        '1' => 'Тест 2',
                        '2' => 'Test section 2',
                    ],
                    'section_description' => [
                        '1' => 'Тест описание 2',
                        '2' => 'Test section description 2',
                    ],
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield [
            'sectionId' => 12,
            'payload' => [
                'kb_section' => [
                    'section_title' => 'Updated Section Name',
                    'section_description' => 'Updated section description',
                    'category_id' => 3,
                ],
            ],
            'response' => [
                'kb_section' => [
                    'section_id' => 12,
                    'category_id' => 3,
                    'section_title' => 'Updated Section Name',
                    'section_description' => 'Updated section description',
                    'active' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $sectionId, array $payload, array $response): void
    {
        $url = $this->host.str_replace('.json', "/{$sectionId}.json", self::API_URL_KB_SECTION);

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->knowledgeBase()->updateSection($sectionId, UpdateKnowledgeBaseSectionPayload::from($payload));

        $payload = UpdateKnowledgeBaseSectionPayload::from($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(UpdateKnowledgeBaseSectionResponse::from($response), $responseData);
    }
}
