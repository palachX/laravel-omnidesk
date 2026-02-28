<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreLabel;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreLabel\LabelStoreData;
use Palach\Omnidesk\UseCases\V1\StoreLabel\Payload as StoreLabelPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreLabelPayloadTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'label data' => [
            'data' => [
                'label' => [
                    'label_title' => 'Test title',
                ],
            ],

            'expected' => new StoreLabelPayload(
                label: new LabelStoreData(
                    labelTitle: 'Test title'
                )
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, StoreLabelPayload $expected): void
    {
        $payload = StoreLabelPayload::from($data);

        $this->assertEquals($expected, $payload);
    }

    #[DataProvider('dataArrayProvider')]
    public function testToArray(array $data, StoreLabelPayload $expected): void
    {
        $payload = StoreLabelPayload::from($data);

        $this->assertEquals($data, $payload->toArray());
    }
}
