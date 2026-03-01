<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteLabel;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteLabel\Payload as DeleteLabelPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteLabelPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'id' => 123,
            ],

            'expected' => new DeleteLabelPayload(
                id: 123,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteLabelPayload $expected): void
    {
        $actual = DeleteLabelPayload::validateAndCreate($data);
        $this->assertEquals($expected->toArray(), $actual->toArray());
    }
}
