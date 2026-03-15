<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\CustomFieldData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCustomFieldList\Response as FetchCustomFieldListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchCustomFieldListTest extends AbstractTestCase
{
    private const string API_URL_CUSTOM_FIELDS = '/api/custom_fields.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'response' => [
                '0' => [
                    'custom_field' => [
                        'field_id' => 5,
                        'title' => 'Field title 1',
                        'field_type' => 'text',
                        'field_level' => 'user',
                        'active' => true,
                        'field_data' => '',
                    ],
                ],
                '1' => [
                    'custom_field' => [
                        'field_id' => 6,
                        'title' => 'Field title 2',
                        'field_type' => 'textarea',
                        'field_level' => 'case',
                        'active' => true,
                        'field_data' => '',
                    ],
                ],
                '2' => [
                    'custom_field' => [
                        'field_id' => 7,
                        'title' => 'Field title 3',
                        'field_type' => 'checkbox',
                        'field_level' => 'user',
                        'active' => true,
                        'field_data' => '',
                    ],
                ],
                '3' => [
                    'custom_field' => [
                        'field_id' => 9,
                        'title' => 'Field title 4',
                        'field_type' => 'select',
                        'field_level' => 'case',
                        'active' => true,
                        'field_data' => [
                            '1' => 'First choice',
                            '2' => 'Second choice',
                            '3' => 'Third choice',
                        ],
                    ],
                ],
                '4' => [
                    'custom_field' => [
                        'field_id' => 10,
                        'title' => 'Field title 5',
                        'field_type' => 'date',
                        'field_level' => 'case',
                        'active' => true,
                        'field_data' => '',
                    ],
                ],
                'total_count' => 10,
            ],
        ];
        yield 'no pagination' => [
            'response' => [
                '0' => [
                    'custom_field' => [
                        'field_id' => 1,
                        'title' => 'Single Field',
                        'field_type' => 'text',
                        'field_level' => 'user',
                        'active' => true,
                        'field_data' => '',
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $response): void
    {
        $url = self::API_URL_CUSTOM_FIELDS;
        $fullUrl = $this->host.$url;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->customFields()->fetchList();

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $totalCount = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{custom_field: array<string, mixed>}> $customFieldsRaw
         */
        $customFieldsRaw = array_values($response);

        $customFields = collect($customFieldsRaw)
            ->map(function (array $item) {
                return CustomFieldData::from($item['custom_field']);
            });

        $this->assertEquals(new FetchCustomFieldListResponse(
            customFields: $customFields,
            totalCount: $totalCount
        ), $list);
    }
}
