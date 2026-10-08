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
        'api_key' => env('YANDEX_MAP_API_KEY', ''),
        'suggest_api_key' => env('YANDEX_MAP_SUGGEST_API_KEY', ''),
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

### Form component

```php
->schema([
    YandexMap::make('point')
        // Set mode of geo-object. Always required!
        ->usingPlacemark()
        // or set mode of geo-object with geo-object properties and options
        ->usingPlacemark(
            new PlacemarkProperties(),
            new PlacemarkOptions(),
        )
        // or set mode of geo-object using mode() method
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
        // Search control is shown by default, with the large size
        ->searchControl()
        ->searchControlSize(SearchControlSize::Large)
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
        // It's required to set up `formatStateUsing`, `dehydrateStateUsing` methods
        // to properly take data from record. The package provides some implementations
        // for common cases. Find more information further below. 
        ->usingArray('lat', 'lng')
        ->usingMagellan() 
])
```

### Infolist component

```php
->schema([
    YandexMapEntry::make('point')
        // Set mode of geo-object. Always required!
        ->usingPlacemark()
        // By default, values are taken from config
        // You are free to override them using plain values or closure
        ->apiKey('your_yandex_api_key')
        ->suggestApiKey('your_yandex_suggest_api_key')
        ->center([53.35, 83.75])
        ->zoom(12)
        ->lang('ru_RU')
        // By default: 600px
        ->height('600px')  
        // It's required to set up `getStateUsing` method
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
- **MultiPolygon**

### MultiPolygon

Use `usingMultiPolygon()` when a geo-object consists of several separate contours,
e.g. a service area of two cities. The rings of one polygon can't be used for that:
they are an outer contour and holes.

```php
YandexMap::make('area')
    ->usingMultiPolygon()
    // or with properties and options which are applied to every polygon
    ->usingMultiPolygon(
        new PolygonProperties(),
        new PolygonOptions(),
    )
    ->usingArray() // or ->usingMagellan()
```

The state is a list of polygons, each polygon is a list of rings (an outer contour
and, optionally, holes), each ring is a closed list of `[lat, lng]` points.
Empty state is `null`.

```php
[
    [[[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [53.0, 83.0]]],   // polygon with one ring
    [[[55.0, 85.0], [55.0, 86.0], [56.0, 86.0], [55.0, 85.0]]],   // another polygon
]
```

Control buttons work with all polygons: "Draw" starts a new polygon and keeps the existing ones,
"Edit" toggles editing of every polygon, "Delete" removes all of them.

## Storing geometries in database

Since there are different ways to store geo-object data in database table, the
package cannot cover all options. But it provides two out-of-the-box usage options:

- json column
- postgis column

### Json column

The package uses array of two coordinates `[lat, lng]` for storing point coordinates.
E.g.: `[53.35, 83.75]`, where `53.35` is latitude and `83.75` is longitude.

If you're store coordinates of point as json-object, e.g.: `{"lat": 53.35, "lng": 83.75}`, then you
should use `->usingArray('lat', 'lng')` method.

### Postgis column (PostgreSQL)

For ease of working with postgis columns it's recommended to
use [Laravel Magellan](https://github.com/clickbar/laravel-magellan) package.

The package supports work with the following geometries:

| Class                                            | Migration                                      |
|--------------------------------------------------|------------------------------------------------|
| `Clickbar\Magellan\Data\Geometries\Point`        | `$table->magellanPoint('point')`               |
| `Clickbar\Magellan\Data\Geometries\LineString`   | `$table->magellanLineString('line')`           |
| `Clickbar\Magellan\Data\Geometries\Polygon`      | `$table->magellanPolygon('polygon')`           |
| `Clickbar\Magellan\Data\Geometries\MultiPolygon` | `$table->magellanMultiPolygon('multipolygon')` |

### Separate lat/lng columns

If you are storing latitude and longitude of the point in the different columns
of your database table, then you need to write your own implementation
of `formatStateUsing` and mutate data before saving.

```php
// On the form
use Kpebedko22\FilamentYandexMap\Forms\Components\YandexMap;
use Kpebedko22\FilamentYandexMap\ValueObjects\Point;
use Kpebedko22\FilamentYandexMap\Enums\YandexMapMode;

YandexMap::make('point')
    ->usingPlacemark()
    ->formatStateUsing(static function (?Model $record) {
        return $record
            ? (new Point($record->lat, $record->lng))->toArray()
            : null;
    }),

// CreatePage
protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['lat'] = $data['point']['lat'];
    $data['lng'] = $data['point']['lng'];
    unset($data['point']);
    
    return parent::mutateFormDataBeforeCreate($data);
}

// EditPage
protected function mutateFormDataBeforeSave(array $data): array
{
    $data['lat'] = $data['point']['lat'];
    $data['lng'] = $data['point']['lng'];
    unset($data['point']);

    return parent::mutateFormDataBeforeSave($data);
}
```

## Localization

The list of available locales can be found in `YandexMapLang` enum.

## Geometries control buttons

List of available control buttons:

- toggle editing mode
- toggle drawing mode
- delete geo-object

Since the form may require using several map components, there is a nuance with
the automatic inclusion of `editor.startDrawing()`, `editor.startEditing()`.
The last loaded map component will dominate. Therefore, in order to
edit/draw on the map, control buttons are added that include these functions.
There is also a button to delete a geo-object.

Default state of control buttons:

```php
YandexMap::make('point')
    ->usingPlacemark()
    ->deleteBtnParameters(
        new ButtonData(__('filament-yandex-map::control-buttons.delete')),
        new ButtonOptions(
            float: ButtonFloat::Right,
            selectOnClick: false
        ),
    );
    ->drawBtnParameters(
        new ButtonData(__('filament-yandex-map::control-buttons.draw')),
        new ButtonOptions(float: ButtonFloat::Right),
    );
    ->editBtnParameters(
        new ButtonData(__('filament-yandex-map::control-buttons.edit')),
        new ButtonOptions(float: ButtonFloat::Right),
    );
```

You are free to change the visual state of this control buttons.

## Search control

The form map shows an address search control in the top left corner. By default
it has the large size, which takes the whole top row of a narrow map (for example,
on a phone) and covers the control buttons. Turn the search control off or make it smaller:

```php
use Kpebedko22\FilamentYandexMap\Enums\SearchControlSize;

YandexMap::make('area')
    ->usingPolygon()
    // Hide the search control
    ->searchControl(false)
    // or choose its size: Small, Medium or Large (default)
    ->searchControlSize(SearchControlSize::Small);
```

Both methods accept a closure. `searchControlSize()` also accepts a plain
string (`'small'`, `'medium'`, `'large'`). The infolist component has no search control.

`Small` and `Medium` show only a button (an icon or "Search"). A tap opens the search
field; on a narrow map it takes the whole top row, covering the control buttons, until
you fold it back with the arrow.

## Geometries properties and options

You can modify geo-object properties and options.

## Testing

Tests are written with [Pest](https://pestphp.com) on top of [Orchestra Testbench](https://packages.tools/testbench).
Install the dependencies with `composer install` and run:

```bash
composer test
```

## Sandbox

Detailed information about sandbox you can find in [Sandbox README](./sandbox/README.md) file.

### How to set up and run

Setup `./sandbox/.env` file.

Create a symlink to the `.env` file in the project root:

```bash
ln -sf ./sandbox/.env ./.env
```

Build and start Docker containers:

```bash
make build
make up
```

Run Artisan commands inside the `fpm` container.

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
