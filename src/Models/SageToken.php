<?php

declare(strict_types=1);

namespace ChrisJohnLeah\SageAccounting\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Persisted Sage OAuth token. A single row is maintained (the connection),
 * overwritten on every refresh so Sage's rotated refresh token is never stale.
 *
 * @property int $id
 * @property string $access_token
 * @property string|null $refresh_token
 * @property Carbon|null $expires_at
 * @property string|null $business_id
 */
class SageToken extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        $table = config('sage.table', 'sage_tokens');

        return is_string($table) ? $table : 'sage_tokens';
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }
}
