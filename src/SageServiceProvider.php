<?php

declare(strict_types=1);

namespace ChrisJohnLeah\SageAccounting\Laravel;

use ChrisJohnLeah\SageAccounting\Contracts\TokenStore;
use ChrisJohnLeah\SageAccounting\Laravel\Commands\SageConnectCommand;
use ChrisJohnLeah\SageAccounting\Laravel\Commands\SageStatusCommand;
use ChrisJohnLeah\SageAccounting\Sage;
use ChrisJohnLeah\SageAccounting\SageConnector;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sage.php', 'sage');

        $this->app->singleton(TokenStore::class, EloquentTokenStore::class);

        $this->app->singleton(SageConnector::class, fn (): SageConnector => new SageConnector(
            clientId: $this->stringConfig('sage.client_id'),
            clientSecret: $this->stringConfig('sage.client_secret'),
            redirectUri: $this->stringConfig('sage.redirect_uri'),
            scopes: $this->scopesConfig(),
            baseUrl: $this->stringConfig('sage.base_url', 'https://api.accounting.sage.com/v3.1'),
            authorizeEndpoint: $this->stringConfig('sage.authorize_endpoint', 'https://www.sageone.com/oauth2/auth/central'),
            tokenEndpoint: $this->stringConfig('sage.token_endpoint', 'https://oauth.accounting.sage.com/token'),
        ));

        $this->app->singleton(Sage::class, fn (): Sage => new Sage(
            $this->app->make(SageConnector::class),
            $this->app->make(TokenStore::class),
            $this->intConfig('sage.refresh_buffer_seconds', 60),
        ));
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'sage');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SageConnectCommand::class,
                SageStatusCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/sage.php' => $this->app->configPath('sage.php'),
            ], 'sage-config');

            $this->publishes([
                __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'sage-migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => $this->app->resourcePath('views/vendor/sage'),
            ], 'sage-views');
        }
    }

    private function stringConfig(string $key, string $default = ''): string
    {
        $value = config($key, $default);

        return is_scalar($value) ? (string) $value : $default;
    }

    private function intConfig(string $key, int $default): int
    {
        $value = config($key, $default);

        return is_numeric($value) ? (int) $value : $default;
    }

    /**
     * @return list<string>
     */
    private function scopesConfig(): array
    {
        $value = config('sage.scopes', []);

        if (! is_array($value)) {
            return [];
        }

        $scopes = [];

        foreach ($value as $scope) {
            if (is_scalar($scope)) {
                $scopes[] = (string) $scope;
            }
        }

        return $scopes;
    }
}
