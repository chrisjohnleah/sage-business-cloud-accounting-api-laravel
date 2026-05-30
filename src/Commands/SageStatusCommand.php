<?php

declare(strict_types=1);

namespace ChrisJohnLeah\SageAccounting\Laravel\Commands;

use ChrisJohnLeah\SageAccounting\Contracts\TokenStore;
use Illuminate\Console\Command;

class SageStatusCommand extends Command
{
    protected $signature = 'sage:status';

    protected $description = 'Show the current Sage connection status.';

    public function handle(TokenStore $store): int
    {
        $token = $store->get();

        if ($token === null) {
            $this->warn('Not connected to Sage. Run `php artisan sage:connect` to begin.');

            return self::FAILURE;
        }

        $this->info('Connected to Sage.');
        $this->table(['Field', 'Value'], [
            ['Business', $token->businessId ?? '(not resolved yet)'],
            ['Access token', substr($token->accessToken, 0, 6).'…'],
            ['Refresh token', $token->refreshToken !== null ? 'present' : 'missing'],
            ['Expires at', $token->expiresAt?->format('Y-m-d H:i:s') ?? 'unknown'],
            ['Expired', $token->hasExpired() ? 'YES — will refresh on next call' : 'no'],
        ]);

        return self::SUCCESS;
    }
}
