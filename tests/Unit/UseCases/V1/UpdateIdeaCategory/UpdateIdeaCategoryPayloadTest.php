<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateIdeaCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaCategory\IdeaCategoryUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaCategory\Payload as UpdateIdeaCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateIdeaCategoryPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'ideas_category' => [
                    'category_title' => 'Test Category Updated',
                    'category_default_group' => 43983,
                ],
            ],

            'expected' => new UpdateIdeaCategoryPayload(
                ideasCategory: new IdeaCategoryUpdateData(
                    categoryTitle: 'Test Category Updated',
                    categoryDefaultGroup: 43983
                )
            ),
        ];

        yield 'partial data - title only' => [
            'data' => [
                'ideas_category' => [
                    'category_title' => 'Title Only Update',
                ],
            ],

            'expected' => new UpdateIdeaCategoryPayload(
                ideasCategory: new IdeaCategoryUpdateData(
                    categoryTitle: 'Title Only Update'
                )
            ),
        ];

        yield 'partial data - group only' => [
            'data' => [
                'ideas_category' => [
                    'category_default_group' => 43984,
                ],
            ],

            'expected' => new UpdateIdeaCategoryPayload(
                ideasCategory: new IdeaCategoryUpdateData(
                    categoryDefaultGroup: 43984
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testValidateAndCreate(array $data, UpdateIdeaCategoryPayload $expected): void
    {
        $actual = UpdateIdeaCategoryPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
