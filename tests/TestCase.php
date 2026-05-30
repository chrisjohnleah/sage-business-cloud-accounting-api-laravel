<?php

declare(strict_types=1);

namespace ChrisJohnLeah\SageAccounting\Laravel\Tests;

use ChrisJohnLeah\SageAccounting\Laravel\SageServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [SageServiceProvider::class];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('sage.client_id', 'test-client');
        $app['config']->set('sage.client_secret', 'test-secret');
        $app['config']->set('sage.redirect_uri', 'https://app.test/oauth/sage/callback');
        $app['config']->set('sage.scopes', ['readonly']);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
