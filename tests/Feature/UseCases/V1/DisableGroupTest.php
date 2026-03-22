<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableGroup\Payload as DisableGroupPayload;
use Palach\Omnidesk\UseCases\V1\DisableGroup\Response as DisableGroupResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DisableGroupTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'disable group' => [
            'groupId' => 200,
            'replaceGroupId' => 300,
            'response' => [
                'group' => [
                    'group_id' => 200,
                    'group_title' => 'Test group 2',
                    'group_from_name' => 'Test group 2 from name',
                    'group_signature' => 'Test group 2 signature',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $groupId, int $replaceGroupId, array $response): void
    {
        $url = $this->host."/api/groups/$groupId/disable.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $payload = new DisableGroupPayload($groupId, $replaceGroupId);
        $responseData = $this->makeHttpClient()->groups()->disableGroup($payload);

        Http::assertSent(function (Request $request) use ($url, $replaceGroupId) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode(['group' => ['replace_group_id' => $replaceGroupId]]);
        });

        $this->assertEquals(DisableGroupResponse::from($response), $responseData);
    }
}
