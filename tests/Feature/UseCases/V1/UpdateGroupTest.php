<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateGroup\Payload as UpdateGroupPayload;
use Palach\Omnidesk\UseCases\V1\UpdateGroup\Response as UpdateGroupResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UpdateGroupTest extends AbstractTestCase
{
    private const string API_URL_GROUPS = '/api/groups.json';

    public static function dataArrayProvider(): iterable
    {
        yield [
            'groupId' => 200,
            'payload' => [
                'group' => [
                    'group_title' => 'Test group 2',
                    'group_from_name' => 'Test group 2 from name',
                ],
            ],
            'response' => [
                'group' => [
                    'group_id' => 200,
                    'group_title' => 'Test group 2',
                    'group_from_name' => 'Test group 2 from name',
                    'group_signature' => 'Test group 2 signature',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield [
            'groupId' => 201,
            'payload' => [
                'group' => [
                    'group_title' => 'Support Team Updated',
                    'group_signature' => 'Best support team signature',
                ],
            ],
            'response' => [
                'group' => [
                    'group_id' => 201,
                    'group_title' => 'Support Team Updated',
                    'group_from_name' => 'Support Team',
                    'group_signature' => 'Best support team signature',
                    'active' => true,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Thu, 25 Dec 2014 15:30:00 +0200',
                ],
            ],
        ];
        yield [
            'groupId' => 202,
            'payload' => [
                'group' => [
                    'group_title' => 'Sales Group',
                    'group_from_name' => 'Sales Department',
                    'group_signature' => 'Sales team signature',
                ],
            ],
            'response' => [
                'group' => [
                    'group_id' => 202,
                    'group_title' => 'Sales Group',
                    'group_from_name' => 'Sales Department',
                    'group_signature' => 'Sales team signature',
                    'active' => true,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $groupId, array $payload, array $response): void
    {
        $url = $this->host.str_replace('.json', "/{$groupId}.json", self::API_URL_GROUPS);

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->groups()->update($groupId, UpdateGroupPayload::from($payload));

        $payload = UpdateGroupPayload::from($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(UpdateGroupResponse::from($response), $responseData);
    }
}
