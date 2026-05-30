<?php

declare(strict_types=1);

namespace ChrisJohnLeah\SageAccounting\Laravel\Facades;

use ChrisJohnLeah\SageAccounting\Sage as SageClient;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string authorizationUrl(?string $state = null, string $country = 'gb')
 * @method static ?string generatedState()
 * @method static \ChrisJohnLeah\SageAccounting\Auth\StoredToken exchangeCode(string $code, ?string $state = null, ?string $expectedState = null)
 * @method static \ChrisJohnLeah\SageAccounting\SageConnector connector()
 * @method static \ChrisJohnLeah\SageAccounting\Auth\StoredToken refresh(\ChrisJohnLeah\SageAccounting\Auth\StoredToken $token)
 * @method static ?\ChrisJohnLeah\SageAccounting\Data\Business resolveBusiness()
 * @method static \ChrisJohnLeah\SageAccounting\Resources\BusinessesResource businesses()
 * @method static \ChrisJohnLeah\SageAccounting\Resources\ContactsResource contacts()
 * @method static \ChrisJohnLeah\SageAccounting\Resources\PurchaseInvoicesResource purchaseInvoices()
 *
 * @see SageClient
 */
class Sage extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SageClient::class;
    }
}
