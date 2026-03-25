<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\EnableIdeaCategory;

use Palach\Omnidesk\DTO\IdeaCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\EnableIdeaCategory\Response as EnableIdeaCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class EnableIdeaCategoryResponseTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'enable idea category response' => [
            'data' => [
                'ideas_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category 2',
                    'category_default_group' => 43983,
                    'active' => true,
                ],
            ],
            'expected' => [
                'ideas_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category 2',
                    'category_default_group' => 43983,
                    'active' => true,
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testResponse(array $data, array $expected): void
    {
        $response = EnableIdeaCategoryResponse::from($data);

        $this->assertInstanceOf(IdeaCategoryData::class, $response->ideasCategory);
        $this->assertEquals($expected, $response->toArray());
    }
}
