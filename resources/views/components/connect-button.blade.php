@props(['label' => 'Connect to Sage'])

<a
    href="{{ \ChrisJohnLeah\SageAccounting\Laravel\Facades\Sage::authorizationUrl() }}"
    {{ $attributes->merge(['class' => 'sage-connect-button']) }}
>
    {{ $label }}
</a>
