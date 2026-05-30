# Sage Business Cloud Accounting — Laravel

[![CI](https://github.com/chrisjohnleah/sage-business-cloud-accounting-api-laravel/actions/workflows/ci.yml/badge.svg)](https://github.com/chrisjohnleah/sage-business-cloud-accounting-api-laravel/actions/workflows/ci.yml)
[![PHP Version](https://img.shields.io/badge/php-%E2%89%A58.3-777bb4.svg)](https://php.net)
[![Laravel](https://img.shields.io/badge/laravel-11%20%7C%2012%20%7C%2013-ff2d20.svg)](https://laravel.com)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

The Laravel bridge for [`chrisjohnleah/sage-business-cloud-accounting-api`](https://github.com/chrisjohnleah/sage-business-cloud-accounting-api). Adds a service provider, a `Sage` facade, an Eloquent token store, artisan commands, and a Blade connect button — so a Laravel app can talk to Sage with zero wiring.

## Requirements

- PHP 8.3+
- Laravel 11, 12, or 13

## Installation

```bash
composer require chrisjohnleah/sage-business-cloud-accounting-api-laravel
php artisan migrate          # creates the sage_tokens table
php artisan vendor:publish --tag=sage-config   # optional
```

Set your Sage Developer app credentials in `.env`:

```dotenv
SAGE_CLIENT_ID=...
SAGE_CLIENT_SECRET=...
SAGE_REDIRECT_URI=https://your-app.test/oauth/sage/callback
SAGE_SCOPES=readonly          # or full_access
```

## Connecting

Render the connect button in a Blade view:

```blade
<x-sage::connect-button label="Connect to Sage" class="btn btn-primary" />
```

…or print the URL from the console:

```bash
php artisan sage:connect
php artisan sage:status        # check the connection
```

In your OAuth callback route, complete the handshake:

```php
use ChrisJohnLeah\SageAccounting\Laravel\Facades\Sage;

Route::get('/oauth/sage/callback', function (Request $request) {
    Sage::exchangeCode($request->query('code'), $request->query('state'), session('sage_state'));
    Sage::resolveBusiness();
    return redirect('/dashboard')->with('status', 'Connected to Sage');
});
```

## Using it

Everything from the core SDK is available through the `Sage` facade — token refresh and pagination are automatic:

```php
use ChrisJohnLeah\SageAccounting\Laravel\Facades\Sage;

foreach (Sage::purchaseInvoices()->list(['updated_or_created_since' => $since]) as $invoice) {
    SupplierBill::updateOrCreate(
        ['sage_invoice_id' => $invoice->id],
        [
            'supplier'     => $invoice->contactName,
            'outstanding'  => $invoice->outstandingAmount,
            'due_date'     => $invoice->dueDate,
            'status'       => $invoice->status?->displayedAs,
        ],
    );
}
```

You can also resolve the client directly: `app(\ChrisJohnLeah\SageAccounting\Sage::class)`.

## Configuration

Publish `config/sage.php` to customise endpoints, scopes, the token table name, and the refresh buffer.

## Testing

```bash
composer check   # Pint + Larastan (max) + Pest
```

## Licence

MIT © [Chris John Leah](https://github.com/chrisjohnleah). See [LICENSE](LICENSE).

> Not affiliated with or endorsed by The Sage Group plc.
