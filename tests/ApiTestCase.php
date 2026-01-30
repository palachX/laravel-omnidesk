<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class ApiTestCase extends AbstractTestCase
{
    use RefreshDatabase;
}
