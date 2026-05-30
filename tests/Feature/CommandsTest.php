<?php

declare(strict_types=1);

use ChrisJohnLeah\SageAccounting\Auth\StoredToken;
use ChrisJohnLeah\SageAccounting\Contracts\TokenStore;
use Illuminate\Support\Facades\Blade;

it('reports a not-connected status', function () {
    $this->artisan('sage:status')->assertFailed();
});

it('reports a connected status once a token exists', function () {
    app(TokenStore::class)->put(
        new StoredToken('access-token', 'refresh', new DateTimeImmutable('+5 minutes'), 'biz-guid-1'),
    );

    $this->artisan('sage:status')->assertSuccessful();
});

it('prints the authorization URL via sage:connect', function () {
    $this->artisan('sage:connect')->assertSuccessful();
});

it('renders the connect-button Blade component', function () {
    $html = Blade::render('<x-sage::connect-button label="Link Sage" />');

    expect($html)->toContain('Link Sage')
        ->and($html)->toContain('oauth2/auth/central')
        ->and($html)->toContain('sage-connect-button');
});
