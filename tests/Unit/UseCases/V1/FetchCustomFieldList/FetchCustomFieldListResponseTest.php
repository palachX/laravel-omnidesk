<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCustomFieldList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\CustomFieldData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCustomFieldList\Response as FetchCustomFieldListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchCustomFieldListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with multiple custom fields' => [
            'data' => [
                'custom_fields' => [
                    [
                        'field_id' => 5,
                        'title' => 'Field title 1',
                        'field_type' => 'text',
                        'field_level' => 'user',
                        'active' => true,
                        'field_data' => '',
                    ],
                    [
                        'field_id' => 6,
                        'title' => 'Field title 2',
                        'field_type' => 'textarea',
                        'field_level' => 'case',
                        'active' => true,
                        'field_data' => '',
                    ],
                    [
                        'field_id' => 7,
                        'title' => 'Field title 3',
                        'field_type' => 'checkbox',
                        'field_level' => 'user',
                        'active' => true,
                        'field_data' => '',
                    ],
                    [
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
                    [
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

            'expected' => new FetchCustomFieldListResponse(
                customFields: new Collection([
                    new CustomFieldData(
                        fieldId: 5,
                        title: 'Field title 1',
                        fieldType: 'text',
                        fieldLevel: 'user',
                        active: true,
                        fieldData: '',
                    ),
                    new CustomFieldData(
                        fieldId: 6,
                        title: 'Field title 2',
                        fieldType: 'textarea',
                        fieldLevel: 'case',
                        active: true,
                        fieldData: '',
                    ),
                    new CustomFieldData(
                        fieldId: 7,
                        title: 'Field title 3',
                        fieldType: 'checkbox',
                        fieldLevel: 'user',
                        active: true,
                        fieldData: '',
                    ),
                    new CustomFieldData(
                        fieldId: 9,
                        title: 'Field title 4',
                        fieldType: 'select',
                        fieldLevel: 'case',
                        active: true,
                        fieldData: [
                            '1' => 'First choice',
                            '2' => 'Second choice',
                            '3' => 'Third choice',
                        ],
                    ),
                    new CustomFieldData(
                        fieldId: 10,
                        title: 'Field title 5',
                        fieldType: 'date',
                        fieldLevel: 'case',
                        active: true,
                        fieldData: '',
                    ),
                ]),
                totalCount: 10
            ),
        ];

        yield 'empty custom fields list' => [
            'data' => [
                'custom_fields' => [],
                'total_count' => 0,
            ],

            'expected' => new FetchCustomFieldListResponse(
                customFields: new Collection([]),
                totalCount: 0
            ),
        ];

        yield 'single custom field' => [
            'data' => [
                'custom_fields' => [
                    [
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

            'expected' => new FetchCustomFieldListResponse(
                customFields: new Collection([
                    new CustomFieldData(
                        fieldId: 1,
                        title: 'Single Field',
                        fieldType: 'text',
                        fieldLevel: 'user',
                        active: true,
                        fieldData: '',
                    ),
                ]),
                totalCount: 1
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCustomFieldListResponse $expected): void
    {
        $actual = FetchCustomFieldListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
