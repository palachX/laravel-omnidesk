<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateLabel;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateLabel\LabelUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateLabel\Payload as UpdateLabelPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateLabelPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'label_id' => 200,
                'label' => [
                    'label_title' => 'New label title',
                ],
            ],

            'expected' => new UpdateLabelPayload(
                labelId: 200,
                label: new LabelUpdateData(
                    labelTitle: 'New label title',
                )
            ),
        ];

        yield 'partial data' => [
            'data' => [
                'label_id' => 200,
                'label' => [
                    'label_title' => 'Updated label title',
                ],
            ],

            'expected' => new UpdateLabelPayload(
                labelId: 200,
                label: new LabelUpdateData(
                    labelTitle: 'Updated label title',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateLabelPayload $expected): void
    {
        $actual = UpdateLabelPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
