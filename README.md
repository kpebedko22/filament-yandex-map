# Filament Yandex Map

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kpebedko22/filament-yandex-map.svg?style=flat-square)](https://packagist.org/packages/kpebedko22/filament-yandex-map)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/kpebedko22/filament-yandex-map/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/kpebedko22/filament-yandex-map/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/kpebedko22/filament-yandex-map/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/kpebedko22/filament-yandex-map/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/kpebedko22/filament-yandex-map.svg?style=flat-square)](https://packagist.org/packages/kpebedko22/filament-yandex-map)

TODO: add description

## Installation

You can install the package via composer:

```bash
composer require kpebedko22/filament-yandex-map
```

~~You can publish the config file with:~~

Modify `services.php` config file

This is the contents of the published config file:

```php
return [
    'filament-yandex-map' => [
        // bla-bla-bla
    ],
];
```

## Usage

TODO: add example code
```php
->schema([
    YandexMap::make('point')
        ->required()
])
```

## Testing

No tests yet.

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Alexander Voytsekhovsky](https://github.com/kpebedko22)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
