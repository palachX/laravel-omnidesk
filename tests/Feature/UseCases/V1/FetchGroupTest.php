<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\GroupData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchGroup\Payload as FetchGroupPayload;
use Palach\Omnidesk\UseCases\V1\FetchGroup\Response as FetchGroupResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchGroupTest extends AbstractTestCase
{
    private const string API_URL_GROUP = '/api/groups/%d.json';

    public static function dataProvider(): iterable
    {
        yield 'full group data' => [
            'groupId' => 200,
            'response' => [
                'group' => [
                    'group_id' => 200,
                    'group_title' => 'Test group',
                    'group_from_name' => 'Test group from name',
                    'group_signature' => 'Test group signature',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield 'minimal group data' => [
            'groupId' => 201,
            'response' => [
                'group' => [
                    'group_id' => 201,
                    'group_title' => 'Simple group',
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(int $groupId, array $response): void
    {
        $payload = new FetchGroupPayload($groupId);

        $url = sprintf(self::API_URL_GROUP, $groupId);
        $fullUrl = $this->host.$url;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $group = $this->makeHttpClient()->groups()->getGroup($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $this->assertEquals(new FetchGroupResponse(
            group: GroupData::from($response['group'])
        ), $group);
    }
}
