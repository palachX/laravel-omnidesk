<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchMacroListTest extends AbstractTestCase
{
    private const string API_URL_MACROS = '/api/macros.json';

    public static function dataProvider(): iterable
    {
        yield 'full data with common and personal macros' => [
            'response' => [
                'common' => [
                    '0' => [
                        'title' => 'Без категории',
                        'sort' => 99999,
                        'macros_category_id' => 0,
                        'data' => [
                            '196529' => [
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
                                    [
                                        'macro_action_id' => 866679,
                                        'action_type' => 'group_id',
                                        'action_display_name' => 'Выставить группу',
                                        'action_destination' => '81765',
                                        'content' => '',
                                        'subject' => '',
                                        'position' => 2,
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
                            '196528' => [
                                'title' => 'Благодарность за лестные слова + статус Закрытое',
                                'position' => 1,
                                'group_name' => 'Общие вопросы',
                                'actions' => [
                                    [
                                        'macro_action_id' => 866613,
                                        'action_type' => 'email_to_user',
                                        'action_display_name' => 'Отправить ответ пользователю',
                                        'action_destination' => [
                                            '1' => 'Спасибо за столь лестные слова. Очень приятно :)<br><br>Если у вас возникнут какие-либо вопросы, смело обращайтесь.',
                                        ],
                                        'content' => '',
                                        'subject' => '',
                                        'position' => 1,
                                    ],
                                    [
                                        'macro_action_id' => 866614,
                                        'action_type' => 'status',
                                        'action_display_name' => 'Изменить статус на',
                                        'action_destination' => '3',
                                        'content' => '',
                                        'subject' => '',
                                        'position' => 2,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        yield 'empty macros' => [
            'response' => [
                'common' => [],
                'personal' => [],
            ],
        ];

        yield 'only common macros' => [
            'response' => [
                'common' => [
                    '0' => [
                        'title' => 'Без категории',
                        'sort' => 99999,
                        'macros_category_id' => 0,
                        'data' => [
                            '196530' => [
                                'title' => 'Планирование демонстрации',
                                'position' => 0,
                                'group_name' => '',
                                'actions' => [
                                    [
                                        'macro_action_id' => 865057,
                                        'action_type' => 'email_to_user',
                                        'action_display_name' => 'Отправить ответ пользователю',
                                        'action_destination' => [
                                            '1' => 'Здравствуйте!<br><br>Можем запланировать демонстрацию [день недели] с 00:00 по 00:00. Сообщите удобное для вас время, и мы отправим вам приглашение.',
                                            '2' => 'Hello!<br><br>We can schedule a demo on [day of the week] from 00:00 to 00:00. Let us know the time convenient for you and we will send you an invitation.',
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
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $response): void
    {
        $url = self::API_URL_MACROS;
        $fullUrl = $this->host.$url;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->macros()->fetchList();

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $this->assertIsIterable($list->common);
        $this->assertIsIterable($list->personal);
    }
}
