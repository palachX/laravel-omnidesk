<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\KnowledgeBaseSectionData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSectionList\Payload as FetchKnowledgeBaseSectionListPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSectionList\Response as FetchKnowledgeBaseSectionListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchKnowledgeBaseSectionListTest extends AbstractTestCase
{
    private const string API_URL_KB_SECTIONS = '/api/kb_section.json';

    public static function dataProvider(): iterable
    {
        yield 'full data with multiple sections' => [
            'response' => [
                '0' => [
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
                '1' => [
                    'kb_section' => [
                        'section_id' => 11,
                        'category_id' => 1,
                        'section_title' => 'Test section 2',
                        'section_description' => 'Test section description 2',
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
                'category_id' => '1',
            ],
        ];

        yield 'multilingual sections' => [
            'response' => [
                '0' => [
                    'kb_section' => [
                        'section_id' => 10,
                        'category_id' => 1,
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
                '1' => [
                    'kb_section' => [
                        'section_id' => 11,
                        'category_id' => 1,
                        'section_title' => [
                            '1' => 'Тест 2',
                            '2' => 'Test section 2',
                        ],
                        'section_description' => [
                            '1' => 'Тест описание 2',
                            '2' => 'Test section description 2',
                        ],
                        'active' => false,
                        'created_at' => 'Mon, 15 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 13 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total_count' => 10,
            ],
            'payload' => [
                'language_id' => 'all',
                'category_id' => '1',
            ],
        ];

        yield 'single section' => [
            'response' => [
                '0' => [
                    'kb_section' => [
                        'section_id' => 1,
                        'category_id' => 1,
                        'section_title' => 'Single section',
                        'section_description' => 'Single section description',
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total_count' => 1,
            ],
            'payload' => [
                'category_id' => '1',
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $response, array $payload): void
    {
        $payload = FetchKnowledgeBaseSectionListPayload::from($payload);

        $url = sprintf(self::API_URL_KB_SECTIONS, $payload->categoryId);
        $query = http_build_query($payload->toQuery());
        $fullUrl = $this->host.$url;

        if ($query !== '') {
            $fullUrl .= '?'.$query;
        }

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->knowledgeBase()->fetchSectionList($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $totalCount = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{kb_section: array<string, mixed>}> $sectionsRaw
         */
        $sectionsRaw = array_values($response);

        $sections = collect($sectionsRaw)
            ->map(function (array $item) {
                return KnowledgeBaseSectionData::from($item['kb_section']);
            });

        $this->assertEquals(new FetchKnowledgeBaseSectionListResponse(
            kbSections: $sections,
            total: $totalCount
        ), $list);
    }
}
