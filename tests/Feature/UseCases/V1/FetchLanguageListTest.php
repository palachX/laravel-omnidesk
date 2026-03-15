<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\LanguageData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchLanguageList\Response as FetchLanguageListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchLanguageListTest extends AbstractTestCase
{
    private const string API_URL_LANGUAGES = '/api/languages.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'response' => [
                '0' => [
                    'language' => [
                        'language_id' => 1,
                        'code' => 'РУС',
                        'title' => 'Русский',
                        'active' => true,
                    ],
                ],
                '1' => [
                    'language' => [
                        'language_id' => 2,
                        'code' => 'ENG',
                        'title' => 'English',
                        'active' => false,
                    ],
                ],
                'total_count' => 2,
            ],
        ];
        yield 'no pagination' => [
            'response' => [
                '0' => [
                    'language' => [
                        'language_id' => 1,
                        'code' => 'РУС',
                        'title' => 'Русский',
                        'active' => true,
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $response): void
    {
        $url = self::API_URL_LANGUAGES;
        $fullUrl = $this->host.$url;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->languages()->fetchList();

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $totalCount = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{language: array<string, mixed>}> $languagesRaw
         */
        $languagesRaw = array_values($response);

        $languages = collect($languagesRaw)
            ->map(function (array $item) {
                return LanguageData::from($item['language']);
            });

        $this->assertEquals(new FetchLanguageListResponse(
            languages: $languages,
            totalCount: $totalCount
        ), $list);
    }
}
