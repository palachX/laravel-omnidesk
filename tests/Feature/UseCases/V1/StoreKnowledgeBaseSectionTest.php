<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseSection\Payload as StoreKnowledgeBaseSectionPayload;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseSection\Response as StoreKnowledgeBaseSectionResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class StoreKnowledgeBaseSectionTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield [
            'payload' => [
                'kb_section' => [
                    'section_title' => 'Test section 2',
                    'section_description' => 'Test section description',
                    'category_id' => '1',
                ],
            ],
            'response' => [
                'kb_section' => [
                    'section_id' => 10,
                    'category_id' => 1,
                    'section_title' => 'Test section 2',
                    'section_description' => 'Test section description',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield [
            'payload' => [
                'kb_section' => [
                    'section_title' => [
                        '1' => 'Тест 2',
                        '2' => 'Test section 2',
                    ],
                    'section_description' => [
                        '1' => 'Тестовое описание 2',
                        '2' => 'Test section description 2',
                    ],
                    'category_id' => '1',
                ],
            ],
            'response' => [
                'kb_section' => [
                    'section_id' => 11,
                    'category_id' => 1,
                    'section_title' => [
                        '1' => 'Тест 2',
                        '2' => 'Test section 2',
                    ],
                    'section_description' => [
                        '1' => 'Тестовое описание 2',
                        '2' => 'Test section description 2',
                    ],
                    'active' => true,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Thu, 25 Dec 2014 15:30:00 +0200',
                ],
            ],
        ];
        yield [
            'payload' => [
                'kb_section' => [
                    'section_title' => 'Stored Section Name',
                    'category_id' => '1',
                ],
            ],
            'response' => [
                'kb_section' => [
                    'section_id' => 12,
                    'category_id' => 1,
                    'section_title' => 'Stored Section Name',
                    'active' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = StoreKnowledgeBaseSectionPayload::validateAndCreate($payload);
        $url = $this->host.'/api/kb_section.json';

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->knowledgeBase()->storeSection($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(StoreKnowledgeBaseSectionResponse::from($response), $responseData);
    }
}
