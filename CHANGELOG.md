# Changelog

All notable changes are documented here, following [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Laravel bridge for the Sage Business Cloud Accounting SDK.
- `SageServiceProvider` binding the `Sage` client, connector, and an Eloquent-backed `TokenStore`.
- `Sage` facade.
- `sage_tokens` migration and `EloquentTokenStore` (single-row, overwrites on refresh).
- Artisan commands: `sage:connect`, `sage:status`.
- Blade component `<x-sage::connect-button>`.
- Publishable config (`sage-config`), migrations (`sage-migrations`), and views (`sage-views`).
- Supports Laravel 11, 12, and 13 on PHP 8.3+.

[Unreleased]: https://github.com/chrisjohnleah/sage-business-cloud-accounting-api-laravel/commits/main
