<?php

namespace Corcel\WooCommerce\Tests;

use Corcel\Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'wp',
            '--realpath' => __DIR__ . '/database/migrations',
        ]);

        $this->withFactories(__DIR__ . '/database/factories');
    }
}
