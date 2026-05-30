<?php

declare(strict_types=1);

namespace ChrisJohnLeah\SageAccounting\Laravel\Commands;

use ChrisJohnLeah\SageAccounting\Sage;
use Illuminate\Console\Command;

class SageConnectCommand extends Command
{
    protected $signature = 'sage:connect';

    protected $description = 'Print the Sage authorization URL to begin the OAuth connection.';

    public function handle(Sage $sage): int
    {
        $this->info('Visit this URL to authorise access to your Sage account:');
        $this->newLine();
        $this->line($sage->authorizationUrl());
        $this->newLine();
        $this->comment('Sage will redirect to your configured callback (SAGE_REDIRECT_URI) to finish connecting.');

        return self::SUCCESS;
    }
}
