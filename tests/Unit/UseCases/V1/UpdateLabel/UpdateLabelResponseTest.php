<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateLabel;

use Palach\Omnidesk\DTO\LabelData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateLabel\Response as UpdateLabelResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateLabelResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full response' => [
            'data' => [
                'label' => [
                    'label_id' => 200,
                    'label_title' => 'New label title',
                ],
            ],

            'expected' => new UpdateLabelResponse(
                label: new LabelData(
                    labelId: 200,
                    labelTitle: 'New label title',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateLabelResponse $expected): void
    {
        $actual = UpdateLabelResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
