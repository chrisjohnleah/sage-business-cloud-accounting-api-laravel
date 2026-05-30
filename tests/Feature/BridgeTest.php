<?php

declare(strict_types=1);

use ChrisJohnLeah\SageAccounting\Auth\StoredToken;
use ChrisJohnLeah\SageAccounting\Contracts\TokenStore;
use ChrisJohnLeah\SageAccounting\Laravel\EloquentTokenStore;
use ChrisJohnLeah\SageAccounting\Laravel\Facades\Sage as SageFacade;
use ChrisJohnLeah\SageAccounting\Laravel\Models\SageToken;
use ChrisJohnLeah\SageAccounting\Sage;
use ChrisJohnLeah\SageAccounting\SageConnector;

it('resolves the Sage client and connector from the container', function () {
    expect(app(Sage::class))->toBeInstanceOf(Sage::class)
        ->and(app(SageConnector::class))->toBeInstanceOf(SageConnector::class);
});

it('binds an Eloquent-backed token store', function () {
    expect(app(TokenStore::class))->toBeInstanceOf(EloquentTokenStore::class);
});

it('round-trips a token through the Eloquent store and keeps a single row', function () {
    $store = app(TokenStore::class);

    expect($store->get())->toBeNull();

    $store->put(new StoredToken('access-1', 'refresh-1', new DateTimeImmutable('+5 minutes'), 'biz-guid-1'));

    expect($store->get()->accessToken)->toBe('access-1')
        ->and($store->get()->refreshToken)->toBe('refresh-1')
        ->and($store->get()->businessId)->toBe('biz-guid-1')
        ->and(SageToken::count())->toBe(1);

    // A refresh overwrites the same row (Sage rotates the refresh token).
    $store->put(new StoredToken('access-2', 'refresh-2', new DateTimeImmutable('+5 minutes'), 'biz-guid-1'));

    expect($store->get()->accessToken)->toBe('access-2')
        ->and($store->get()->refreshToken)->toBe('refresh-2')
        ->and(SageToken::count())->toBe(1);

    $store->forget();
    expect($store->get())->toBeNull();
});

it('exposes the Sage facade backed by the client', function () {
    expect(SageFacade::getFacadeRoot())->toBeInstanceOf(Sage::class);
});

it('builds an authorization URL through the facade', function () {
    $url = SageFacade::authorizationUrl(state: 'csrf-state');

    expect($url)->toContain('sageone.com/oauth2/auth/central')
        ->and($url)->toContain('client_id=test-client')
        ->and($url)->toContain('filter=apiv3.1')
        ->and($url)->toContain('state=csrf-state');
});
