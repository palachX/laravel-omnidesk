<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UnlinkUser\Payload as UnlinkUserPayload;
use Palach\Omnidesk\UseCases\V1\UnlinkUser\Response as UnlinkUserResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UnlinkUserTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'unlink user' => [
            'userId' => 1307386,
            'payload' => [
                'user_id' => 25830712,
            ],
            'response' => [
                'user' => [
                    'user_id' => 1307386,
                    'user_full_name' => "User's full name",
                    'company_name' => "User's company name",
                    'company_position' => "User's position",
                    'thumbnail' => '',
                    'confirmed' => false,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2020 10:55:23 +0200',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                    'type' => 'telegram',
                    'telegram_id' => 495582869,
                    'linked_users' => [1307386],
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $userId, array $payload, array $response): void
    {
        $payload = UnlinkUserPayload::from($payload);

        $url = $this->host."/api/users/$userId/unlink.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->users()->unlinkUser($userId, $payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(UnlinkUserResponse::from($response), $responseData);
    }
}
