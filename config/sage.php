<?php

declare(strict_types=1);

return [
    /*
     * OAuth2 credentials from your Sage Developer app
     * (https://developerselfservice.sageone.com).
     */
    'client_id' => env('SAGE_CLIENT_ID'),
    'client_secret' => env('SAGE_CLIENT_SECRET'),
    'redirect_uri' => env('SAGE_REDIRECT_URI'),

    /*
     * 'readonly' or 'full_access'. Comma-separated for multiple.
     */
    'scopes' => array_values(array_filter(
        array_map('trim', explode(',', (string) env('SAGE_SCOPES', 'readonly'))),
    )),

    'country' => env('SAGE_COUNTRY', 'gb'),

    /*
     * Endpoints — defaults target Sage Business Cloud Accounting (GB).
     */
    'base_url' => env('SAGE_API_BASE_URL', 'https://api.accounting.sage.com/v3.1'),
    'authorize_endpoint' => env('SAGE_AUTHORIZE_ENDPOINT', 'https://www.sageone.com/oauth2/auth/central'),
    'token_endpoint' => env('SAGE_TOKEN_ENDPOINT', 'https://oauth.accounting.sage.com/token'),

    /*
     * The table the Eloquent token store reads/writes. Override if your app
     * already uses `sage_tokens` for something else.
     */
    'table' => env('SAGE_TOKEN_TABLE', 'sage_tokens'),

    /*
     * Refresh the access token this many seconds before it expires.
     */
    'refresh_buffer_seconds' => 60,
];
