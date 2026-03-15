<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchLanguageList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\LanguageData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchLanguageList\Response as FetchLanguageListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchLanguageListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with multiple languages' => [
            'data' => [
                'languages' => [
                    [
                        'language_id' => 1,
                        'code' => 'РУС',
                        'title' => 'Русский',
                        'active' => true,
                    ],
                    [
                        'language_id' => 2,
                        'code' => 'ENG',
                        'title' => 'English',
                        'active' => false,
                    ],
                ],
                'total_count' => 2,
            ],

            'expected' => new FetchLanguageListResponse(
                languages: new Collection([
                    new LanguageData(
                        languageId: 1,
                        code: 'РУС',
                        title: 'Русский',
                        active: true,
                    ),
                    new LanguageData(
                        languageId: 2,
                        code: 'ENG',
                        title: 'English',
                        active: false,
                    ),
                ]),
                totalCount: 2
            ),
        ];

        yield 'empty languages list' => [
            'data' => [
                'languages' => [],
                'total_count' => 0,
            ],

            'expected' => new FetchLanguageListResponse(
                languages: new Collection([]),
                totalCount: 0
            ),
        ];

        yield 'single language' => [
            'data' => [
                'languages' => [
                    [
                        'language_id' => 1,
                        'code' => 'РУС',
                        'title' => 'Русский',
                        'active' => true,
                    ],
                ],
                'total_count' => 1,
            ],

            'expected' => new FetchLanguageListResponse(
                languages: new Collection([
                    new LanguageData(
                        languageId: 1,
                        code: 'РУС',
                        title: 'Русский',
                        active: true,
                    ),
                ]),
                totalCount: 1
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchLanguageListResponse $expected): void
    {
        $actual = FetchLanguageListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
