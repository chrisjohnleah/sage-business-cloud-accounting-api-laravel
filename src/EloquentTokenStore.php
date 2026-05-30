<?php

declare(strict_types=1);

namespace ChrisJohnLeah\SageAccounting\Laravel;

use ChrisJohnLeah\SageAccounting\Auth\StoredToken;
use ChrisJohnLeah\SageAccounting\Contracts\TokenStore;
use ChrisJohnLeah\SageAccounting\Laravel\Models\SageToken;

/**
 * Stores the Sage connection's token in a single Eloquent row. put() overwrites
 * that row so Sage's rotated refresh token always replaces the previous one.
 */
final class EloquentTokenStore implements TokenStore
{
    public function get(): ?StoredToken
    {
        $row = SageToken::query()->latest('id')->first();

        if ($row === null) {
            return null;
        }

        return new StoredToken(
            accessToken: $row->access_token,
            refreshToken: $row->refresh_token,
            expiresAt: $row->expires_at?->toDateTimeImmutable(),
            businessId: $row->business_id,
        );
    }

    public function put(StoredToken $token): void
    {
        $attributes = [
            'access_token' => $token->accessToken,
            'refresh_token' => $token->refreshToken,
            'expires_at' => $token->expiresAt,
            'business_id' => $token->businessId,
        ];

        $existing = SageToken::query()->latest('id')->first();

        if ($existing !== null) {
            $existing->update($attributes);

            return;
        }

        SageToken::query()->create($attributes);
    }

    public function forget(): void
    {
        SageToken::query()->delete();
    }
}
