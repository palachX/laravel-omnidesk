<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\Facades;

use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Clients\MessagesClient;
use Palach\Omnidesk\Clients\NotesClient;
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Tests\AbstractTestCase;

final class OmnideskTest extends AbstractTestCase
{
    public function testOmnideskFacadeReturnsCasesClient(): void
    {
        $cases = Omnidesk::cases();

        $this->assertInstanceOf(CasesClient::class, $cases);
    }

    public function testOmnideskFacadeReturnsMessagesClient(): void
    {
        $messages = Omnidesk::messages();

        $this->assertInstanceOf(MessagesClient::class, $messages);
    }

    public function testOmnideskFacadeReturnsNotesClient(): void
    {
        $notes = Omnidesk::notes();

        $this->assertInstanceOf(NotesClient::class, $notes);
    }

    public function testOmnideskFacadeResolvesToOmnideskService(): void
    {
        $omnideskRoot = Omnidesk::getFacadeRoot();
        $omnideskService = app('omnidesk');

        $this->assertSame($omnideskService, $omnideskRoot);
    }

    public function testOmnideskFacadeRootIsCorrectInstance(): void
    {
        $omnideskRoot = Omnidesk::getFacadeRoot();

        $this->assertInstanceOf(\Palach\Omnidesk\Omnidesk::class, $omnideskRoot);
    }

    public function testOmnideskFacadeMethodsWorkCorrectly(): void
    {
        // Test that facade methods return the correct client types
        $cases = Omnidesk::cases();
        $messages = Omnidesk::messages();
        $notes = Omnidesk::notes();

        $this->assertInstanceOf(CasesClient::class, $cases);
        $this->assertInstanceOf(MessagesClient::class, $messages);
        $this->assertInstanceOf(NotesClient::class, $notes);

        // Test that multiple calls to the same method return the same client (singleton behavior)
        $cases1 = Omnidesk::cases();
        $cases2 = Omnidesk::cases();
        $this->assertSame($cases1, $cases2);
    }
}
