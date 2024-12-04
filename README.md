# Filament Yandex Map

This package provides a set of tools for using Yandex Map within
the [Laravel Filament](https://github.com/filamentphp/filament).

## Installation

You can install the package via composer:

```bash
composer require kpebedko22/filament-yandex-map
```

Modify `services.php` config file. This is the contents of the config file:

```php
return [
    // ...
    
    'yandex_map' => [
        'api_key' => env('YANDEX_MAP_API_KEY'),
        'suggest_api_key' => env('YANDEX_MAP_SUGGEST_API_KEY'),
        'lang' => 'ru_RU',
        'center' => [53.35, 83.75],
        'zoom' => 12,
    ],
];
```

Optionally, you can publish the translations using:

```bash
php artisan vendor:publish --tag="filament-yandex-map-translations"
```

## Usage

```php
->schema([
    YandexMap::make('point')
        // Set mode of geo-object. Always required!
        ->mode(YandexMapMode::Placemark) 
        // By default, values are taken from config
        // You are free to override them using plain values or closure
        ->apiKey('your_yandex_api_key')
        ->suggestApiKey('your_yandex_suggest_api_key')
        ->center([53.35, 83.75])
        ->zoom(12)
        ->lang('ru_RU')
        // By default: 600px
        ->height('600px')
        // Setup control buttons  
        ->deleteBtnParameters(
            new ButtonData(__('filament-yandex-map::control-buttons.delete')),
            new ButtonOptions(
                float: ButtonFloat::Right,
                selectOnClick: false
            ),
        )
        ->drawBtnParameters()
        ->editBtnParameters()
        // Setup geo-object
        ->geoObjectOptions(new GeoObjectOptions())
        ->geoObjectProperties(new GeoObjectProperties())
        // It's required to set up `formatStateUsing`, `dehydrateStateUsing` methods
        // to properly take data from record. The package provides some implementations
        // for common cases. Find more information further below. 
        ->usingArray('lat', 'lng')
        ->usingMagellan() 
])
```

## Geometries

The package provides work with the following types of geometries (geo-objects):

- **Point**
- **Linestring**
- **Polygon**

## Storing geometries in database

Since there are different ways to store geo-object data in database table, the
package cannot cover all options. But it provides two out-of-the-box usage options:

- json column
- postgis column

### Json column

The package uses array of two coordinates `[lat, lng]` for storing point coordinates.
E.g.: `[53.35, 83.75]`, where `53.35` is latitude and `83.75` is longitude.

If you're store coordinates of point as json-object, e.g.: `{"lat": 53.35, "lng": 83.75}`, then you
should use `->usingJson('lat', 'lng')` method.

### Postgis column (PostgreSQL)

For ease of working with postgis columns it's recommended to
use [Laravel Magellan](https://github.com/clickbar/laravel-magellan) package.

The package supports work with the following geometries:

| Class                                          | Migration                            |
|------------------------------------------------|--------------------------------------|
| `Clickbar\Magellan\Data\Geometries\Point`      | `$table->magellanPoint('point')`     |
| `Clickbar\Magellan\Data\Geometries\LineString` | `$table->magellanLineString('line')` |
| `Clickbar\Magellan\Data\Geometries\Polygon`    | `$table->magellanPolygon('polygon')` |

### Separate lat/lng columns

If you are storing latitude and longitude of the point in the different columns
of your database table, then you need to write your own implementation
of `formatStateUsing`, `dehydrateStateUsing` methods.

## Localization

The list of available locales can be found in `YandexMapLang` enum.

TODO: add the following info:

- modify control buttons
- modify geo object properties and options

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
