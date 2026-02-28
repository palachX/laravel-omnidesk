<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchLabelList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\LabelData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchLabelList\Response as FetchLabelListResponse;

final class FetchLabelListResponseTest extends AbstractTestCase
{
    public function testResponse(): void
    {
        $labels = collect([
            new LabelData(
                labelId: 200,
                labelTitle: 'Test title'
            ),
            new LabelData(
                labelId: 210,
                labelTitle: 'Test title 2'
            ),
        ]);

        $response = new FetchLabelListResponse(
            labels: $labels,
            total: 10
        );

        $this->assertEquals($labels, $response->labels);
        $this->assertEquals(10, $response->total);
    }

    public function testEmptyResponse(): void
    {
        $labels = new Collection;

        $response = new FetchLabelListResponse(
            labels: $labels,
            total: 0
        );

        $this->assertTrue($response->labels->isEmpty());
        $this->assertEquals(0, $response->total);
    }
}
