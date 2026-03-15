<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchMacroList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\MacroActionData;
use Palach\Omnidesk\DTO\MacroCategoryData;
use Palach\Omnidesk\DTO\MacroData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchMacroList\Response as FetchMacroListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchMacroListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with common and personal macros' => [
            'data' => [
                'common' => [
                    [
                        'title' => 'Без категории',
                        'sort' => 99999,
                        'macros_category_id' => 0,
                        'data' => [
                            [
                                'title' => 'Требуется помощь бухгалтерии',
                                'position' => 0,
                                'group_name' => 'Общие вопросы',
                                'actions' => [
                                    [
                                        'macro_action_id' => 866678,
                                        'action_type' => 'add_note',
                                        'action_display_name' => 'Добавить заметку',
                                        'action_destination' => '',
                                        'content' => '<notify rel="g81765">@Вопросы по оплате</notify> нужна помощь с обращением.',
                                        'subject' => '',
                                        'position' => 1,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'personal' => [
                    [
                        'title' => 'Без категории',
                        'sort' => 99999,
                        'macros_category_id' => 0,
                        'data' => [
                            [
                                'title' => 'Благодарность за лестные слова',
                                'position' => 1,
                                'group_name' => 'Общие вопросы',
                                'actions' => [
                                    [
                                        'macro_action_id' => 866613,
                                        'action_type' => 'email_to_user',
                                        'action_display_name' => 'Отправить ответ пользователю',
                                        'action_destination' => 'Спасибо за лестные слова.',
                                        'content' => '',
                                        'subject' => '',
                                        'position' => 1,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            'expected' => new FetchMacroListResponse(
                common: new Collection([
                    new MacroCategoryData(
                        title: 'Без категории',
                        sort: 99999,
                        macrosCategoryId: 0,
                        data: new Collection([
                            new MacroData(
                                title: 'Требуется помощь бухгалтерии',
                                position: 0,
                                actions: new Collection([
                                    new MacroActionData(
                                        macroActionId: 866678,
                                        actionType: 'add_note',
                                        actionDisplayName: 'Добавить заметку',
                                        actionDestination: '',
                                        position: 1,
                                        content: '<notify rel="g81765">@Вопросы по оплате</notify> нужна помощь с обращением.',
                                        subject: '',
                                    ),
                                ]),
                                groupName: 'Общие вопросы',
                            ),
                        ]),
                    ),
                ]),
                personal: new Collection([
                    new MacroCategoryData(
                        title: 'Без категории',
                        sort: 99999,
                        macrosCategoryId: 0,
                        data: new Collection([
                            new MacroData(
                                title: 'Благодарность за лестные слова',
                                position: 1,
                                actions: new Collection([
                                    new MacroActionData(
                                        macroActionId: 866613,
                                        actionType: 'email_to_user',
                                        actionDisplayName: 'Отправить ответ пользователю',
                                        actionDestination: 'Спасибо за лестные слова.',
                                        position: 1,
                                        content: '',
                                        subject: '',
                                    ),
                                ]),
                                groupName: 'Общие вопросы',
                            ),
                        ]),
                    ),
                ]),
            ),
        ];

        yield 'empty macros' => [
            'data' => [
                'common' => [],
                'personal' => [],
            ],

            'expected' => new FetchMacroListResponse(
                common: new Collection([]),
                personal: new Collection([]),
            ),
        ];

        yield 'only common macros' => [
            'data' => [
                'common' => [
                    [
                        'title' => 'Без категории',
                        'sort' => 99999,
                        'macros_category_id' => 0,
                        'data' => [
                            [
                                'title' => 'Планирование демонстрации',
                                'position' => 0,
                                'group_name' => '',
                                'actions' => [
                                    [
                                        'macro_action_id' => 865057,
                                        'action_type' => 'email_to_user',
                                        'action_display_name' => 'Отправить ответ пользователю',
                                        'action_destination' => [
                                            '1' => 'Здравствуйте!',
                                        ],
                                        'content' => '',
                                        'subject' => '',
                                        'position' => 1,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'personal' => [],
            ],

            'expected' => new FetchMacroListResponse(
                common: new Collection([
                    new MacroCategoryData(
                        title: 'Без категории',
                        sort: 99999,
                        macrosCategoryId: 0,
                        data: new Collection([
                            new MacroData(
                                title: 'Планирование демонстрации',
                                position: 0,
                                actions: new Collection([
                                    new MacroActionData(
                                        macroActionId: 865057,
                                        actionType: 'email_to_user',
                                        actionDisplayName: 'Отправить ответ пользователю',
                                        actionDestination: [
                                            '1' => 'Здравствуйте!',
                                        ],
                                        position: 1,
                                        content: '',
                                        subject: '',
                                    ),
                                ]),
                                groupName: '',
                            ),
                        ]),
                    ),
                ]),
                personal: new Collection([]),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchMacroListResponse $expected): void
    {
        $actual = FetchMacroListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
