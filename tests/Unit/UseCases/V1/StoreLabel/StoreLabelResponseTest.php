<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreLabel;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreLabel\Response as StoreLabelResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreLabelResponseTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'label response' => [
            'data' => [
                'label' => [
                    'label_id' => 200,
                    'label_title' => 'Test title',
                ],
            ],

            'expected' => new StoreLabelResponse(
                label: new \Palach\Omnidesk\DTO\LabelData(
                    labelId: 200,
                    labelTitle: 'Test title'
                )
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, StoreLabelResponse $expected): void
    {
        $response = StoreLabelResponse::from($data);

        $this->assertEquals($expected, $response);
    }

    #[DataProvider('dataArrayProvider')]
    public function testToArray(array $data, StoreLabelResponse $expected): void
    {
        $response = StoreLabelResponse::from($data);

        $this->assertEquals($data, $response->toArray());
    }
}
