<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\BlockUser\Payload as BlockUserPayload;
use Palach\Omnidesk\UseCases\V1\BlockUser\Response as BlockUserResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class BlockUserTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'block user' => [
            'userId' => 200,
            'response' => [
                'user' => [
                    'user_id' => 200,
                    'user_full_name' => "User's full name changed",
                    'company_name' => "User's company name",
                    'company_position' => "User's position",
                    'thumbnail' => '',
                    'confirmed' => false,
                    'active' => false,
                    'deleted' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'type' => 'email',
                    'user_email' => 'user@domain.ru',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $userId, array $response): void
    {
        $url = $this->host."/api/users/$userId/block.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $payload = new BlockUserPayload($userId);
        $responseData = $this->makeHttpClient()->users()->blockUser($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(BlockUserResponse::from($response), $responseData);
    }
}
